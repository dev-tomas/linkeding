<?php
include("conexion.php");

if (isset($_POST['id_empresa']) && isset($_POST['nuevo_estado'])) {
    $id_empresa = $_POST['id_empresa'];
    $nuevo_estado = $_POST['nuevo_estado'];

    // Obtener el ID correspondiente al nuevo estado (Activo/Inactivo)
    $query_estado = "SELECT id_estado_empresa FROM estado_empresa WHERE nombre_estado_empresa = '$nuevo_estado'";
    $resultado_estado = mysqli_query($cn, $query_estado);
    $estado = mysqli_fetch_assoc($resultado_estado)['id_estado_empresa'];

    // Actualizar el estado de la empresa
    $sql_update = "UPDATE empresa SET id_estado_empresa = $estado WHERE id_empresa = $id_empresa";

    if (mysqli_query($cn, $sql_update)) {
        echo json_encode(['success' => true, 'message' => 'Estado cambiado a ' . $nuevo_estado . ' correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al cambiar el estado: ' . mysqli_error($cn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos para cambiar el estado.']);
}
?>
