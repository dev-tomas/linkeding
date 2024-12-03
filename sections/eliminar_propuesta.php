<?php
include("conexion.php");

$id = $_GET["id"];

$sql = "DELETE FROM propuesta WHERE id_propuesta = $id";

if (mysqli_query($cn, $sql)) {
    // Redirigir con éxito
    header('Location: ../index.php?page=reporte_propuesta');
} else {
    // Redirigir con error
    header('Location: ../index.php?page=reporte_propuesta');
}

exit();
?>
