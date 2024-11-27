<?php

include("conexion.php");


$propuesta = $_POST['propuesta'];
$descripcion = $_POST['descripcion'];
$requisitos = $_POST['requisitos'];
$fechalimite = $_POST['fechalimite'];
$id_estado_propuesto = $_POST["lstestado"];


$id_estado_propuesto = 2;


$sql = "insert into propuesta (nombre_propuesta, descripcion_propuesta, requisitos_propuesta, fecha_limite, id_estado_propuesta) 
        values ('$propuesta', '$descripcion', '$requisitos', '$fechalimite', '$id_estado_propuesto')";


mysqli_query($cn, $sql);

header("Location: reportepropuesta.php");

?>


