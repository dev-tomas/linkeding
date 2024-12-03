<?php
include("conexion.php");

if (isset($_POST['id_postulante']) && isset($_POST['nuevo_estado'])) {
    $id_postulante = $_POST['id_postulante'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $query_estado = "SELECT id_estado_postulante FROM estado_postulante WHERE nombre_estado_postulante = '$nuevo_estado'";
    $resultado_estado = mysqli_query($cn, $query_estado);
    $estado = mysqli_fetch_assoc($resultado_estado)['id_estado_postulante'];

    // Actualizar el estado del postulante
    $sql_update = "UPDATE postulante SET id_estado_postulante = $estado WHERE id_postulante = '$id_postulante'";

    if (mysqli_query($cn, $sql_update)) {
        echo json_encode(['success' => true, 'message' => 'Estado cambiado a ' . $nuevo_estado . ' correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al cambiar el estado: ' . mysqli_error($cn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos para cambiar el estado.']);
}
?>
