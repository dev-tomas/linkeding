<?php
include("../conexion.php");

// Verificar si se recibió el ID del mensaje
if (isset($_POST['id_mensaje'])) {
    $id_mensaje = $_POST['id_mensaje'];

    // Obtener el ID del estado "leído"
    $sql_estado = "SELECT id_estado_mensaje FROM estado_mensaje WHERE nombre_estado_mensaje = 'leído'";
    $result_estado = mysqli_query($cn, $sql_estado);
    $row_estado = mysqli_fetch_assoc($result_estado);
    $id_estado_leido = $row_estado['id_estado_mensaje'];

    // Actualizar el estado del mensaje
    $sql_actualizar = "UPDATE mensaje SET id_estado_mensaje = ? WHERE id_mensaje = ?";
    $stmt = mysqli_prepare($cn, $sql_actualizar);
    mysqli_stmt_bind_param($stmt, "ii", $id_estado_leido, $id_mensaje);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Estado del mensaje actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado del mensaje']);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'ID de mensaje no proporcionado']);
}

mysqli_close($cn);
?>