<?php
// Only start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['usuario_id'])) {
    // Redirect to login page or show an error
    header("Location: login.php");
    exit();
}

include("sections/conexion.php");

// Safely retrieve the logged-in user's ID
$id_usuario_logueado = $_SESSION['usuario_id'];

// Prepare the query to find the company ID
$sql_empresa = "SELECT id_empresa FROM empresa WHERE id_usuario = ?";
$stmt_empresa = mysqli_prepare($cn, $sql_empresa);
mysqli_stmt_bind_param($stmt_empresa, "i", $id_usuario_logueado);
mysqli_stmt_execute($stmt_empresa);
$resultado_empresa = mysqli_stmt_get_result($stmt_empresa);

// Check if a company exists for this user
if (mysqli_num_rows($resultado_empresa) > 0) {
    $empresa = mysqli_fetch_assoc($resultado_empresa);
    $id_empresa = $empresa['id_empresa'];

    // Update the state of the proposals to 'expirado' if the current date is past the fecha_limite
    $sql_update = "UPDATE propuesta 
                   SET id_estado_propuesta = (SELECT id_estado_propuesta FROM estado_propuesta WHERE nombre_estado_propuesta = 'expirado')
                   WHERE fecha_limite < CURDATE() AND id_estado_propuesta = (SELECT id_estado_propuesta FROM estado_propuesta WHERE nombre_estado_propuesta = 'activo')";
    mysqli_query($cn, $sql_update);

    // Prepare the main query to get proposals
    $sql = "SELECT p.*, ep.nombre_estado_propuesta
        FROM propuesta p
        INNER JOIN estado_propuesta ep ON p.id_estado_propuesta = ep.id_estado_propuesta
        INNER JOIN detalle_empresa_propuesta dep ON p.id_propuesta = dep.id_propuesta
        WHERE dep.id_empresa = ? AND ep.id_estado_propuesta IN ('1','2')";
    $stmt = mysqli_prepare($cn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_empresa);
    mysqli_stmt_execute($stmt);

    $r = mysqli_stmt_get_result($stmt);
    
} else {
    // No company found for this user
    $r = null;
}
?>