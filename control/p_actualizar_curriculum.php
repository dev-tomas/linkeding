<?php
require_once __DIR__ . '/../sections/conexion.php';

function actualizar_datos_curriculum($id_usuario, $perfil_postulante, $id_carrera, $archivo = null) {
    global $cn;

    // Consultar si existe un curriculum relacionado al usuario
    $sql_select = "SELECT c.id_curriculum, c.ruta_curriculum
                   FROM curriculum c
                   INNER JOIN postulante p ON c.id_curriculum = p.id_curriculum
                   WHERE p.id_usuario = ?";
    $stmt_select = mysqli_prepare($cn, $sql_select);
    if (!$stmt_select) return false;

    mysqli_stmt_bind_param($stmt_select, "i", $id_usuario);
    mysqli_stmt_execute($stmt_select);
    $resultado = mysqli_stmt_get_result($stmt_select);
    $fila = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt_select);

    if (!$fila) return false; // Si no hay curriculum, devolver falso

    $id_curriculum = $fila['id_curriculum'];
    $ruta_anterior = $fila['ruta_curriculum'];

    // Preparar la ruta para un nuevo archivo si se proporciona
    $nueva_ruta = $ruta_anterior;
    if ($archivo && $archivo['tmp_name']) {
        $directorio = '../curriculum/';
        $nombre_archivo = uniqid('curriculum_') . '.pdf';
        $nueva_ruta = $directorio . $nombre_archivo;

        // Mover el archivo subido a la nueva ubicación
        if (!move_uploaded_file($archivo['tmp_name'], $nueva_ruta)) {
            return false; // Error al subir el archivo
        }

        // Eliminar el archivo anterior si existía
        if ($ruta_anterior && file_exists($ruta_anterior)) {
            unlink($ruta_anterior);
        }
    }

    // Actualizar los datos en la tabla curriculum
    $sql_update = "UPDATE curriculum 
                   SET perfil_postulante_curriculum = ?, id_carrera = ?, ruta_curriculum = ?
                   WHERE id_curriculum = ?";
    $stmt_update = mysqli_prepare($cn, $sql_update);
    if (!$stmt_update) return false;

    mysqli_stmt_bind_param($stmt_update, "sisi", $perfil_postulante, $id_carrera, $nueva_ruta, $id_curriculum);
    $resultado_update = mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    return $resultado_update;
}

// Procesar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    // Verificar que el usuario esté autenticado
    if (!isset($_SESSION['usuario_id'])) {
        echo "<script>
                alert('Debe iniciar sesión para realizar esta acción');
                window.location.href = '../index.php';
              </script>";
        exit();
    }

    $id_usuario = $_SESSION['usuario_id'];
    $perfil_postulante = $_POST['txtperfil'] ?? '';
    $id_carrera = $_POST['txtcargo'] ?? '';
    $archivo = $_FILES['archivo'] ?? null;

    // Intentar actualizar los datos del currículum
    if (actualizar_datos_curriculum($id_usuario, $perfil_postulante, $id_carrera, $archivo)) {
        echo "<script>
                alert('Curriculum actualizado exitosamente');
                window.location.href = '../index.php?page=home';
              </script>";
    } else {
        echo "<script>
                alert('Error al actualizar el curriculum. Por favor, intente nuevamente.');
                window.location.href = '../index.php?page=actualizar_curriculum';
              </script>";
    }
}
?>
