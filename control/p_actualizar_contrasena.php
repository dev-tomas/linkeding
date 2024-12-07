<?php

Include __DIR__ .'/../sections/conexion.php';

$idUsuario = $_SESSION["usuario_id"]; // Asegúrate de que auth.php establece esto correctamente

$nuevaContrasena = $_POST["txtpass"];
$repetirContrasena = $_POST["txtrepass"];

// Validaciones
if (strcmp($nuevaContrasena, $repetirContrasena) !== 0) {
    header('location: editar_contrasena_usuario.php?error=contrasenas_no_coinciden'); // Mejor manejo de errores
    exit();
}

if (strlen($nuevaContrasena) < 3) {
    header('location: editar_contrasena_usuario.php?error=contrasena_corta'); // Mejor manejo de errores
    exit();
}

// Hashear la contraseña antes de guardarla.  Es FUNDAMENTAL para la seguridad.
$contrasenaHasheada = password_hash($nuevaContrasena, PASSWORD_DEFAULT);


// Consulta preparada para prevenir inyección SQL
$stmt = $cn->prepare("UPDATE usuario SET contrasena_usuario = ? WHERE id_usuario = ?");
$stmt->bind_param("si", $contrasenaHasheada, $idUsuario);

if ($stmt->execute()) {
    // Éxito al actualizar la contraseña
    session_destroy(); // Destruye la sesión para forzar el cierre de sesión
    header('location: index.php'); // Redirige al login
    exit();
} else {
    // Manejo de errores
    header('location: editar_contrasena_usuario.php?error=actualizacion_fallida');
    exit();
}

$stmt->close();
$cn->close(); // Cierra la conexión
?>