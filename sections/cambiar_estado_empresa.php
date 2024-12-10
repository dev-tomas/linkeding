<?php
include("conexion.php");

// Verificar si se recibió el ID de la empresa
if (isset($_GET['id_empresa'])) {
    // Obtener el ID de la empresa
    $id_empresa = intval($_GET['id_empresa']);

    // Consulta para obtener el estado actual de la empresa
    $query_estado_actual = "SELECT id_estado_empresa FROM empresa WHERE id_empresa = $id_empresa";
    $resultado_estado = mysqli_query($cn, $query_estado_actual);
    
    if ($resultado_estado) {
        $fila = mysqli_fetch_assoc($resultado_estado);
        $estado_actual = $fila['id_estado_empresa'];
        
        // Cambiar el estado (de 1 a 2 o de 2 a 1)
        $nuevo_estado = ($estado_actual == 1) ? 2 : 1;
        
        // Consulta para actualizar el estado
        $query_actualizar = "UPDATE empresa SET id_estado_empresa = $nuevo_estado WHERE id_empresa = $id_empresa";
        mysqli_query($cn, $query_actualizar);
    }
}

// Redirigir siempre a la página de atención de empresas
header("Location: index.php?page=atencion_empresa");
exit();
?>