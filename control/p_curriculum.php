<?php
include("conexion.php");

$cargo=$_POST["txtcargo"];
$perfil=$_POST["txtperfil"];

$cod=0;

$archivo=$_FILES["archivo"]["tmp_name"];


if (trim($e)=="pdf") {
  

    move_uploaded_file($archivo,"curriculum/".$cod.".pdf");
    
    header('location: index.php');


} else {

    header('location: curriculum.php');
}

$sql = "insert into curriculum (perfil_postulante_curriculum, id_carrera) 
 values ('$perfil', $cargo)";

mysqli_query($cn,$sql);

header('location: curriculum.php');

?>