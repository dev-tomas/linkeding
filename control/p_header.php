<?php
// Coloca esta línea al principio para evitar problemas de salida
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("sections/conexion.php");

$id_rol = $_SESSION['id_rol'] ?? 0;

if ($id_rol == 3) {
    $sql_usuario = "SELECT *
                FROM postulante 
                WHERE id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);

    mysqli_stmt_execute($stmt);

    $result_usuario = mysqli_stmt_get_result($stmt);

    $usuario = mysqli_fetch_assoc($result_usuario);
    $nombre = $usuario['nombre_postulante'] ?? 'No especificado';
    $apellido_paterno = $usuario['apellido_paterno_postulante'] ?? 'No especificado';
    $apellido_materno = $usuario['apellido_materno_postulante'] ?? 'No especificado';
    $nombre_titular = $apellido_paterno . '  ' . $apellido_materno . '  ' . $nombre;

} else if ($id_rol == 2) {
    $sql_usuario = "SELECT *
                FROM empresa 
                WHERE id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);

    mysqli_stmt_execute($stmt);

    $result_usuario = mysqli_stmt_get_result($stmt);

    $usuario = mysqli_fetch_assoc($result_usuario);
    $nombre = $usuario['razon_social_empresa'] ?? 'No especificado';
    $representante = $usuario['representante_empresa'] ?? 'No especificado';


} else if ($id_rol == 1) {
    $sql_usuario = "SELECT *
                FROM administrador 
                WHERE id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);

    mysqli_stmt_execute($stmt);

    $result_usuario = mysqli_stmt_get_result($stmt);

    $usuario = mysqli_fetch_assoc($result_usuario);
    $nombre = $usuario['nombre_administrador'] ?? 'No especificado';
    $apellido_paterno = $usuario['apellido_paterno_administrador'] ?? 'No especificado';
    $apellido_materno = $usuario['apellido_materno_administrador'] ?? 'No especificado';
    $nombre_titular = $apellido_paterno . '  ' . $apellido_materno . '  ' . $nombre;

} else {
    header("Location: ../login.php");
}

$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Usuario';
$ruta_imagen_usuario = $_SESSION['ruta_imagen_usuario'] ?? 'linkeding/img/user.svg';

mysqli_stmt_close($stmt);
?>