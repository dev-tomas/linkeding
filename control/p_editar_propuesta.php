<?php
include("../sections/conexion.php");

$id = $_POST['id'];
$propuesta = $_POST['txtpropuesta'];
$descripcion = $_POST['descripcion'];
$requisitos = $_POST['requisitos'];
$fecha_limite = $_POST['fechalimite'];

$sql = "UPDATE propuesta SET 
            nombre_propuesta = '$propuesta', 
            descripcion_propuesta = '$descripcion', 
            requisitos_propuesta = '$requisitos', 
            fecha_limite = '$fecha_limite',
            id_estado_propuesta = 1
        WHERE id_propuesta = $id";


if (mysqli_query($cn, $sql)) {
    // Redirigir con éxito
    header("Location: ../index.php?page=reporte_propuesta");
} else {
    // Mostrar mensaje de error para depuración
    error_log("Error al actualizar: " . mysqli_error($cn));
    header("Location: ../index.php?page=reporte_propuesta");
}
exit();
?>
