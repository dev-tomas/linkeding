<?php
include("conexion.php");

if (isset($_POST['id_postulante'])) {
    $id_postulante = $_POST['id_postulante'];
    $celular_postulante = $_POST['celular_postulante'];
    $direccion_postulante = $_POST['direccion_postulante'];

    
    $sql_update = "UPDATE postulante SET 
                   celular_postulante = '$celular_postulante',
                   direccion_postulante = '$direccion_postulante'
                   WHERE id_postulante = $id_postulante";

    if (mysqli_query($cn, $sql_update)) {
            header("Location: ../index.php?page=reportes_admin");
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el postulante: ' . mysqli_error($cn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos para actualizar.']);
}
?>
