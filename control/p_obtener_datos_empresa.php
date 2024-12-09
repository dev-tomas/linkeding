<?php
require_once __DIR__.'/../sections/conexion.php';
function obtenerDatosEmpresa($id_empresa) {
    global $cn; // Use the global database connection from conexion.php
    
    // SQL query to select all columns from empresa table joined with other necessary tables
    $sql = "SELECT 
                e.id_empresa,
                e.ruc_empresa,
                e.razon_social_empresa,
                e.celular_empresa,
                e.direccion_empresa,
                e.representante_empresa,
                e.id_estado_empresa,
                ee.nombre_estado_empresa,
                u.id_usuario,
                u.nombre_usuario,
                u.ruta_imagen_usuario,
                u.ruta_imagen_portada
            FROM empresa e
            JOIN estado_empresa ee ON e.id_estado_empresa = ee.id_estado_empresa
            JOIN usuario u ON e.id_usuario = u.id_usuario
            WHERE e.id_empresa = ?";
    
    // Prepare the query
    $stmt = mysqli_prepare($cn, $sql);
    
    if (!$stmt) {
        // Handle query preparation errors
        error_log("Error preparing query: " . mysqli_error($cn));
        return null;
    }
    
    // Bind the id_empresa parameter
    mysqli_stmt_bind_param($stmt, "i", $id_empresa);
    
    // Execute the query
    if (!mysqli_stmt_execute($stmt)) {
        // Handle query execution errors
        error_log("Error executing query: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return null;
    }
    
    // Get the query results
    $resultado = mysqli_stmt_get_result($stmt);
    
    if (!$resultado) {
        // Handle result retrieval errors
        error_log("Error getting results: " . mysqli_error($cn));
        mysqli_stmt_close($stmt);
        return null;
    }
    
    // Fetch the company data
    $datosEmpresa = mysqli_fetch_assoc($resultado);
    
    // Close the prepared statement
    mysqli_stmt_close($stmt);
    
    // Return the company data or null if no data found
    return $datosEmpresa ? $datosEmpresa : null;
}