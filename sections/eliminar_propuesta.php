<?php
include("conexion.php");

$id = $_GET["id"];

// Cambiar estado a 'eliminado'
$sql = "UPDATE propuesta 
        SET id_estado_propuesta = (SELECT id_estado_propuesta FROM estado_propuesta WHERE nombre_estado_propuesta = 'eliminado') 
        WHERE id_propuesta = $id";

if (mysqli_query($cn, $sql)) {
    // Redirigir con éxito
    header('Location: index.php?page=reporte_propuesta&status=deleted');
} else {
    // Redirigir con error
    header('Location: index.php?page=reporte_propuesta&status=error');
}

exit();
?>
