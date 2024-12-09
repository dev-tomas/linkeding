<?php

include("../sections/conexion.php");  


session_start();
if (!isset($_SESSION["nombre_usuario"])) {
    header("Location: ../index.php?page=login"); 
    exit;
}

$nombreUsuario = $_SESSION["nombre_usuario"]; 
$nuevaContrasena = $_POST["txtpass"];
$repetirContrasena = $_POST["txtrepass"];

// Validaciones de contraseñas
if (strcmp($nuevaContrasena, $repetirContrasena) !== 0) {
    // Si las contraseñas no coinciden, redirige con un error
    header('location: editar_contrasena_usuario.php?error=contrasenas_no_coinciden');
    exit;
}

if (strlen($nuevaContrasena) < 3) {
    // Si la contraseña es demasiado corta, redirige con un error
    header('location: editar_contrasena_usuario.php?error=contrasena_corta');
    exit;
}

// Obtener el ID del usuario basado en el nombre de usuario
$sqlUsuario = "SELECT id_usuario FROM usuario WHERE nombre_usuario = ?";
$stmtUsuario = $cn->prepare($sqlUsuario);
$stmtUsuario->bind_param('s', $nombreUsuario); // 's' es para un string (nombre_usuario)
$stmtUsuario->execute();
$stmtUsuario->store_result();

if ($stmtUsuario->num_rows > 0) {
    // Si se encuentra el usuario, obtenemos el ID
    $stmtUsuario->bind_result($idUsuario);
    $stmtUsuario->fetch();
    $stmtUsuario->close();

    // Actualizar la contraseña en la base de datos (sin encriptar)
    $sql = "UPDATE usuario SET contrasena_usuario = ? WHERE id_usuario = ?";
    $stmt = $cn->prepare($sql);
    $stmt->bind_param('si', $nuevaContrasena, $idUsuario); // 'si' indica que la contraseña es un string y el id_usuario es entero
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Si la contraseña se actualiza correctamente, destruye la sesión y redirige
        session_destroy();  // Cerrar la sesión
        header('location: ../index.php?page=login');  // Redirige al login
    } else {
        // Si no se pudo actualizar la contraseña, redirige con un error
        header('location: editar_contrasena_usuario.php?error=actualizacion_fallida');
    }

    $stmt->close();
} else {
    // Si no se encuentra el usuario, redirige con un error
    header('location: editar_contrasena_usuario.php?error=usuario_no_encontrado');
}

$cn->close(); // Cerrar la conexión a la base de datos
?>
