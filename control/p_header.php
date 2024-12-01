<?php
// Coloca esta línea al principio para evitar problemas de salida
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("sections/conexion.php");

// Validar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_rol = $_SESSION['id_rol'] ?? 0;
$nombre = 'Invitado';
$nombre_titular = 'Invitado';
$ruta_imagen_usuario = '../img/user.svg'; // Imagen por defecto

if ($id_rol == 3) { // Rol de postulante
    $sql_usuario = "SELECT * FROM postulante WHERE id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);
    mysqli_stmt_execute($stmt);
    $result_usuario = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($result_usuario)) {
        $nombre = $usuario['nombre_postulante'] ?? 'No especificado';
        $apellido_paterno = $usuario['apellido_paterno_postulante'] ?? '';
        $apellido_materno = $usuario['apellido_materno_postulante'] ?? '';
        $nombre_titular = trim("$apellido_paterno $apellido_materno $nombre");
        $ruta_imagen_usuario = $usuario['foto_postulante'] ?? '../img/user.svg';
    }
} elseif ($id_rol == 2) { // Rol de empresa
    $sql_usuario = "SELECT * FROM empresa WHERE id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);
    mysqli_stmt_execute($stmt);
    $result_usuario = mysqli_stmt_get_result($stmt); // <-- Faltaba esta línea

    if ($usuario = mysqli_fetch_assoc($result_usuario)) {
        $nombre = $usuario['razon_social_empresa'] ?? 'No especificado';
        $nombre_titular = $nombre;
        $ruta_imagen_usuario = $usuario['logo_empresa'] ?? '../img/user.svg';
    }
} elseif ($id_rol == 1) { // Rol de administrador
    $sql_usuario = "SELECT * FROM administrador WHERE id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);
    mysqli_stmt_execute($stmt);
    $result_usuario = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($result_usuario)) {
        $nombre = $usuario['nombre_administrador'] ?? 'No especificado';
        $apellido_paterno = $usuario['apellido_paterno_administrador'] ?? '';
        $apellido_materno = $usuario['apellido_materno_administrador'] ?? '';
        $nombre_titular = trim("$apellido_paterno $apellido_materno $nombre");
        $ruta_imagen_usuario = $usuario['foto_administrador'] ?? '../img/user.svg';
    }
} else {
    // Si el rol no es válido, redirige al login
    header("Location: login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Usuario';

mysqli_stmt_close($stmt);
?>
