<?php
require_once 'control/p_home.php';

include("conexion.php");

// Verificar si el usuario está autenticado y tiene el rol de postulante
if (isset($_SESSION['usuario_id'])) {
    $id_postulante = $_SESSION['usuario_id'];  // Obtener el id_postulante de la sesión

    // Consultar la base de datos para obtener las postulaciones de este usuario
    $sql = "SELECT p.nombre_propuesta, dp.fecha_postulacion
            FROM detalle_postulante_propuesta dp
            INNER JOIN propuesta p ON dp.id_propuesta = p.id_propuesta
            WHERE dp.id_postulante = ?";
    
    // Preparar y ejecutar la consulta
    if ($fila = $cn->prepare($sql)) {
        $fila->bind_param("i", $id_postulante);
        $fila->execute();
        $resultado = $fila->get_result();
    } else {
        echo "Error en la consulta: " . $cn->error;
        exit;
    }
} else {
    // Mostrar mensaje de error si el usuario no está autenticado
    echo "Acceso no permitido. Debes iniciar sesión.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Postulaciones</title>
    <link rel="stylesheet" href="css/postulaciones.css">
</head>
<body>
    <h2><strong><center>MIS POSTULACIONES</center></strong></h2>

    <table border="1" cellspacing="0" cellpadding="5" style="margin: 0 auto;">
        <thead>
            <tr>
                <th>Nombre de la Propuesta</th>
                <th>Fecha de Postulación</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verificar si hay resultados
            if ($resultado->num_rows > 0) {
                while ($r = $resultado->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($r['nombre_propuesta']) . "</td>
                            <td>" . htmlspecialchars($r['fecha_postulacion']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2' style='text-align:center;'>No tienes postulaciones registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>


