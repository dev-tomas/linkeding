<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__."/../sections/conexion.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

    $sql_usuario = "
        SELECT p.nombre_postulante, p.apellido_paterno_postulante, p.apellido_materno_postulante, 
               u.ruta_imagen_usuario 
        FROM postulante p
        INNER JOIN usuario u ON p.id_usuario = u.id_usuario
        WHERE u.id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);
    mysqli_stmt_execute($stmt);
    $result_usuario = mysqli_stmt_get_result($stmt);
    if ($usuario = mysqli_fetch_assoc($result_usuario)) {
        $nombre = $usuario['nombre_postulante'] ?? 'No especificado';
    }
// Verificar si se subió un archivo
if (isset($_FILES['archivo'])) {
    $archivo = $_FILES['archivo'];
    $cargo = $_POST["txtcargo"];
    $perfil = $_POST["txtperfil"];

    // Verificar que sea PDF
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if ($extension !== 'pdf') {
        echo "<script>
                alert('Solo se permiten archivos pdf');
                window.location.href = '../index.php?page=curriculum';
                </script>";
        exit();
    }

    // Verificar que el archivo no esté vacío
    if ($archivo['size'] == 0) {
        echo "<script>
                alert('El archivo está vacío');
                window.location.href = '../index.php?page=curriculum';
                </script>";
        exit();
    }

    // Limitar tamaño de archivo (5MB)
    if ($archivo['size'] > 5 * 1024 * 1024) {
        echo "<script>
                alert('El archivo es demasiado grande');
                window.location.href = '../index.php?page=curriculum';
                </script>";
        exit();
    }

    // Generar nombre de archivo único en formato especificado
    $nombre_archivo = $nombre . '_linkeding_curriculum_usuario_.pdf';
    $ruta_destino = '../curriculum/' . $nombre_archivo;

    // Crear directorio si no existe
    if (!file_exists('../curriculum')) {
        mkdir('../curriculum', 0777, true);
    }

    // Intentar mover archivo
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        // Preparar consulta con ruta de curriculum
        $sql = "INSERT INTO curriculum (perfil_postulante_curriculum, id_carrera, ruta_curriculum) VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($cn, $sql);
        mysqli_stmt_bind_param($stmt, "sis", $perfil, $cargo, $nombre_archivo);

        if (mysqli_stmt_execute($stmt)) {
            // Obtener el ID del curriculum recién insertado
            $curriculum_id = mysqli_insert_id($cn);

            // Actualizar el postulante con el ID del curriculum
            $update_sql = "UPDATE postulante SET id_curriculum = ? WHERE id_usuario = ?";
            $update_stmt = mysqli_prepare($cn, $update_sql);

            // Asume que estás trabajando con el usuario actual, ajusta según tu sistema de autenticación
            $user_id = $_SESSION['usuario_id']; // Asegúrate de tener una sesión iniciada
            mysqli_stmt_bind_param($update_stmt, "ii", $curriculum_id, $user_id);

            if (mysqli_stmt_execute($update_stmt)) {
                // Éxito
                echo "<script>
                alert('Curriculum subido exitosamente');
                window.location.href = '../index.php?page=home';
                </script>";
                exit();
            } else {
                // Error al actualizar postulante
                echo "<script>
                alert('Error al actualizar postulante');
                window.location.href = '../index.php?page=curriculum';
                </script>";
                exit();
            }
        } else {
            // Error en inserción: eliminar archivo
            unlink($ruta_destino);
            echo "<script>
                alert('Error en la base de datos');
                window.location.href = '../index.php?page=curriculum';
                </script>";
            exit();
        }
    } else {
        // Error al mover archivo
        echo "<script>
                alert('Error al subir el archivo');
                window.location.href = '../index.php?page=curriculum';
                </script>";
        exit();
    }
} else {
    // No se subió archivo
    echo "<script>
                alert('No se ha seleccionado ningun archivo');
                window.location.href = '../index.php?page=curriculum';
                </script>";
    exit();
}
?>
