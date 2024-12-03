<?php
include("conexion.php");

if (isset($_POST['id_empresa'])) {
    $id_empresa = $_POST['id_empresa'];
    $ruc_empresa = $_POST['ruc_empresa'];
    $razon_social_empresa = $_POST['razon_social_empresa'];
    $celular_empresa = $_POST['celular_empresa'];
    $direccion_empresa = $_POST['direccion_empresa'];
    $representante_empresa = $_POST['representante_empresa'];

    $sql_update = "UPDATE empresa SET 
                   ruc_empresa = '$ruc_empresa',
                   razon_social_empresa = '$razon_social_empresa',
                   celular_empresa = '$celular_empresa',
                   direccion_empresa = '$direccion_empresa',
                   representante_empresa = '$representante_empresa'
                   WHERE id_empresa = $id_empresa";

    if (mysqli_query($cn, $sql_update)) {
        echo json_encode(['success' => true, 'message' => 'Empresa actualizada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la empresa: ' . mysqli_error($cn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos para actualizar.']);
}
?>
