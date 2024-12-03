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

    // Prepare the main query to get proposals
    $sql = "SELECT p.*, e.nombre_estado_propuesta
        FROM propuesta p
        INNER JOIN estado_propuesta e ON p.id_estado_propuesta = e.id_estado_propuesta
        INNER JOIN detalle_empresa_propuesta dep ON p.id_propuesta = dep.id_propuesta
        WHERE dep.id_empresa = ? ";
    $stmt = mysqli_prepare($cn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_empresa);
    mysqli_stmt_execute($stmt);
    $r = mysqli_stmt_get_result($stmt);
} else {
    // No company found for this user
    $r = null;
}
?>