
<?php
include("../sections/conexion.php");

// Validar que todos los campos estén presentes
if (!isset($_POST['propuesta'], $_POST['descripcion'], $_POST['requisitos'], $_POST['fechalimite'])) {
    header("Location: ../sections/propuesta.php?status=error");
    exit;
}

$propuesta = mysqli_real_escape_string($cn, $_POST['propuesta']);
$descripcion = mysqli_real_escape_string($cn, $_POST['descripcion']);
$requisitos = mysqli_real_escape_string($cn, $_POST['requisitos']);
$fechalimite = mysqli_real_escape_string($cn, $_POST['fechalimite']);

$id_estado_propuesto = 1;

// Insertar la propuesta
$sql = "INSERT INTO propuesta (nombre_propuesta, descripcion_propuesta, requisitos_propuesta, fecha_limite, id_estado_propuesta) 
        VALUES ('$propuesta', '$descripcion', '$requisitos', '$fechalimite', '$id_estado_propuesto')";

if (mysqli_query($cn, $sql)) {
    header("Location: ../index.php?page=propuesta");
} else {
    header("Location: ../index.php?page=propuesta");
}

exit;
?>
