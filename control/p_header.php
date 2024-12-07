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

$id_rol = $_SESSION['id_rol'] ?? 0;
$nombre = 'Invitado';
$nombre_titular = 'Invitado';
$ruta_imagen_usuario = 'img/user.svg'; // Imagen por defecto

if ($id_rol == 3) { // Rol de postulante
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
        $apellido_paterno = $usuario['apellido_paterno_postulante'] ?? '';
        $apellido_materno = $usuario['apellido_materno_postulante'] ?? '';
        $nombre_titular = trim("$apellido_paterno $apellido_materno $nombre");

        // Construir la ruta completa de la imagen
        $foto = $usuario['ruta_imagen_usuario'];
        if (!empty($foto)) {
            $ruta_imagen_usuario = 'img/usuario/'. $foto;
            $ruta_imagen_portada = 'img/portada/'. $foto;
        } else {
            $ruta_imagen_usuario = 'img/user.svg'; // Imagen por defecto
            
        }
    }

} elseif ($id_rol == 2) { // Rol de empresa
    $sql_usuario = "SELECT e.*,u.*
        FROM empresa e
        INNER JOIN usuario u ON e.id_usuario = u.id_usuario
        WHERE u.id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);
    mysqli_stmt_execute($stmt);
    $result_usuario = mysqli_stmt_get_result($stmt); // <-- Faltaba esta línea

    if ($usuario = mysqli_fetch_assoc($result_usuario)) {
        $nombre = $usuario['razon_social_empresa'] ?? 'No especificado';
        $nombre_titular = $nombre;
        // Construir la ruta completa de la imagen
        $foto = $usuario['ruta_imagen_usuario'];
        if (!empty($foto)) {
            $ruta_imagen_usuario = 'img/usuario/'. $foto;
            $ruta_imagen_portada = 'img/portada/'. $foto;
        } else {
            $ruta_imagen_usuario = 'img/user.svg'; // Imagen por defecto
        }
    }
} elseif ($id_rol == 1) { // Rol de administrador
    $sql_usuario = "SELECT a.*,u.* 
    FROM administrador a 
    INNER JOIN usuario u ON a.id_usuario = u.id_usuario
    WHERE u.id_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);
    mysqli_stmt_execute($stmt);
    $result_usuario = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($result_usuario)) {
        $nombre = $usuario['nombre_administrador'] ?? 'No especificado';
        $apellido_paterno = $usuario['apellido_paterno_administrador'] ?? '';
        $apellido_materno = $usuario['apellido_materno_administrador'] ?? '';
        $nombre_titular = trim("$apellido_paterno $apellido_materno $nombre");
        // Construir la ruta completa de la imagen
        $foto = $usuario['ruta_imagen_usuario'];
        if (!empty($foto)) {
            $ruta_imagen_usuario = 'img/usuario/'. $foto;
            $ruta_imagen_portada = 'img/portada/'. $foto;
        } else {
            $ruta_imagen_usuario = 'img/user.svg'; // Imagen por defecto
        }
    }
} else {
    // Si el rol no es válido, redirige al login
    header("Location: login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Usuario';

mysqli_stmt_close($stmt);
?>
