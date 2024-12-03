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

include("conexion.php");

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
            WHERE dep.id_empresa = ?";
    $stmt = mysqli_prepare($cn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_empresa);
    mysqli_stmt_execute($stmt);
    $r = mysqli_stmt_get_result($stmt);
} else {
    // No company found for this user
    $r = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Propuestas</title>
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<div class="content">
    <h2>Reporte de Propuestas</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Nombre Propuesta</th>
                <th>Descripción</th>
                <th>Fecha Límite</th>
                <th>Estado</th>  
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($r && mysqli_num_rows($r) > 0) {
                while ($row = mysqli_fetch_assoc($r)) {
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_propuesta']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion_propuesta']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_limite']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_estado_propuesta']); ?></td> 
                    <td>
                        <a href="../index.php?page=editar_propuesta&id=<?php echo $row['id_propuesta']; ?>" class="icon-edit">✎ Editar</a>
                        <a href="../index.php?page=eliminar_propuesta&id=<?php echo $row['id_propuesta']; ?>" class="icon-trash">🗑 Eliminar</a>
                        <a href="../index.php?page=ver_postulante&id=<?php echo $row['id_propuesta']; ?>" class="icon-user">👤 Ver postulantes</a>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5'>No hay propuestas registradas para esta empresa.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>