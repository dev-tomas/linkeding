<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("control/conexion.php");
$id_rol = $_SESSION['id_rol'] ?? 0;


if ($id_rol == 3) {
    $sql_usuario = "SELECT p.*, e.nombre_estado_postulante 
                FROM postulante p
                LEFT JOIN estado_postulante e ON p.id_estado_postulante = e.id_estado_postulante
                WHERE p.id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);

    mysqli_stmt_execute($stmt);

    $result_usuario = mysqli_stmt_get_result($stmt);

    $usuario = mysqli_fetch_assoc($result_usuario);

    $nombre = $usuario['nombre_postulante'] ?? 'No especificado';
    $cip = $usuario['cip_postulante'] ?? 'No especificado';
    $dni = $usuario['dni_postulante'] ?? 'No especificado';
    $apellido_paterno = $usuario['apellido_paterno_postulante'] ?? 'No especificado';
    $apellido_materno = $usuario['apellido_materno_postulante'] ?? 'No especificado';
    $estado = $usuario['id_estado_postulante'] ?? 'Desconocido';
    $nombre_estado_postulante = $usuario['nombre_estado_postulante'] ?? 'Desconocido';
} else if ($id_rol == 2) {
    $sql_usuario = "SELECT p.*, e.nombre_estado_empresa 
                FROM empresa p
                LEFT JOIN estado_empresa e ON p.id_estado_empresa = e.id_estado_empresa
                WHERE p.id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);

    mysqli_stmt_execute($stmt);

    $result_usuario = mysqli_stmt_get_result($stmt);

    $usuario = mysqli_fetch_assoc($result_usuario);

    $razon_social = $usuario['razon_social_empresa'] ?? 'No especificado';
    $representante = $usuario['representante_empresa'] ?? 'No especificado';
    $ruc = $usuario['ruc_empresa'] ?? 'No especificado';
    $estado = $usuario['id_estado_empresa'] ?? 'Desconocido';
    $nombre_estado_empresa = $usuario['nombre_estado_empresa'] ?? 'Desconocido';
} else if ($id_rol == 1)

mysqli_stmt_close($stmt);
?>