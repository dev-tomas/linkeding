<?php
include("conexion.php");

if (isset($_POST['id_postulante'])) {
    $id_postulante = $_POST['id_postulante'];
    $cip_postulante = $_POST['cip_postulante'];
    $dni_postulante = $_POST['dni_postulante'];
    $nombre_postulante = $_POST['nombre_postulante'];
    $apellido_paterno_postulante = $_POST['apellido_paterno_postulante'];
    $apellido_materno_postulante = $_POST['apellido_materno_postulante'];
    $celular_postulante = $_POST['celular_postulante'];
    $direccion_postulante = $_POST['direccion_postulante'];
    $fecha_nacimiento_postulante = $_POST['fecha_nacimiento_postulante'];

    $sql_update = "UPDATE postulante SET 
                   cip_postulante = '$cip_postulante',
                   dni_postulante = '$dni_postulante',
                   nombre_postulante = '$nombre_postulante',
                   apellido_paterno_postulante = '$apellido_paterno_postulante',
                   apellido_materno_postulante = '$apellido_materno_postulante',
                   celular_postulante = '$celular_postulante',
                   direccion_postulante = '$direccion_postulante',
                   fecha_nacimiento_postulante = '$fecha_nacimiento_postulante'
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
