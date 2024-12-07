<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("sections/conexion.php");

// Verifica que la sesión contenga el rol del usuario
$id_rol = $_SESSION['id_rol'] ?? 0;
$usuario_id = $_SESSION['usuario_id'] ?? null;

// Inicializa las variables en caso de que no se asignen más adelante
$nombre = 'No especificado';
$cip = $dni = $apellido_paterno = $apellido_materno = $razon_social = $representante = $ruc = $estado = 'Desconocido';
$nombre_estado_postulante = $nombre_estado_empresa = 'No especificado';

// Verifica si hay un usuario logueado
if ($usuario_id === null) {
    die("Error: No se encontró un usuario activo en la sesión.");
}

if ($id_rol == 3) { // Rol de Postulante
    $sql_usuario = "SELECT p.*, e.nombre_estado_postulante 
                    FROM postulante p
                    LEFT JOIN estado_postulante e ON p.id_estado_postulante = e.id_estado_postulante
                    WHERE p.id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $usuario_id);
        mysqli_stmt_execute($stmt);
        $result_usuario = mysqli_stmt_get_result($stmt);

        if ($result_usuario) {
            $usuario = mysqli_fetch_assoc($result_usuario);


            $nombre_a = $usuario['nombre_postulante'] ?? 'No especificado';
            $cip = $usuario['cip_postulante'] ?? 'No especificado';
            $dni = $usuario['dni_postulante'] ?? 'No especificado';
            $apellido_paterno = $usuario['apellido_paterno_postulante'] ?? 'No especificado';
            $apellido_materno = $usuario['apellido_materno_postulante'] ?? 'No especificado';
            $estado = $usuario['id_estado_postulante'] ?? 'Desconocido';
            $nombre_estado_postulante = $usuario['nombre_estado_postulante'] ?? 'Desconocido';
            $direccion = $usuario['direccion_postulante'] ?? 'Desconocido';
            $celular = $usuario['celular_postulante'] ?? 'Desconocido';
            $id_curriculum = $usuario['id_postulante'];
            $nombre = $nombre_a.' '.$apellido_paterno.' '.$apellido_materno;
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Error en la preparación de la consulta de postulante: " . mysqli_error($cn));
    }
} elseif ($id_rol == 2) { // Rol de Empresa
    $sql_usuario = "SELECT p.*, e.nombre_estado_empresa 
                    FROM empresa p
                    LEFT JOIN estado_empresa e ON p.id_estado_empresa = e.id_estado_empresa
                    WHERE p.id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $usuario_id);
        mysqli_stmt_execute($stmt);
        $result_usuario = mysqli_stmt_get_result($stmt);

        if ($result_usuario) {
            $usuario = mysqli_fetch_assoc($result_usuario);

            $nombre = $usuario['razon_social_empresa'] ?? 'No especificado';
            $representante = $usuario['representante_empresa'] ?? 'No especificado';
            $ruc = $usuario['ruc_empresa'] ?? 'No especificado';
            $estado = $usuario['id_estado_empresa'] ?? 'Desconocido';
            $direccion=$usuario['direccion_empresa']??'Desconocido';
            $celular = $usuario['celular_empresa'] ?? 'Desconocido';
            $nombre_estado_empresa = $usuario['nombre_estado_empresa'] ?? 'Desconocido';
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Error en la preparación de la consulta de empresa: " . mysqli_error($cn));
    }
} elseif ($id_rol == 1) {

    $sql_usuario = "SELECT*
    FROM administrador a
    WHERE id_usuario =?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    // Rol de Administrador


    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $usuario_id);
        mysqli_stmt_execute($stmt);
        $result_usuario = mysqli_stmt_get_result($stmt);

        if ($result_usuario) {
            $usuario = mysqli_fetch_assoc($result_usuario);

            $nombre_a = $usuario['nombre_administrador'] ?? 'Administrador';
            $apellido_paterno = $usuario['apellido_paterno_administrador'] ?? 'No especificado';
            $apellido_materno = $usuario['apellido_materno_administrador'] ?? 'No especificado';
            $nombre=$nombre_a.' '.$apellido_paterno.' '.$apellido_materno;
        }
    }
} else { // Rol desconocido
    die("Error: Rol de usuario no identificado.");
}
?>