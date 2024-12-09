<?php
require_once __DIR__.'/../sections/conexion.php';
function obtenerDatosPostulante($id_postulante) {
    global $cn; // Use the global database connection from conexion.php
    
    // SQL query to select all columns from postulante table with related information
    $sql = "SELECT 
                p.id_postulante,
                p.cip_postulante,
                p.dni_postulante,
                p.nombre_postulante,
                p.apellido_paterno_postulante,
                p.apellido_materno_postulante,
                p.celular_postulante,
                p.direccion_postulante,
                p.fecha_nacimiento_postulante,
                p.id_sexo,
                s.nombre_sexo,
                p.id_estado_postulante,
                ep.nombre_estado_postulante,
                p.id_curriculum,
                u.id_usuario,
                u.nombre_usuario,
                u.ruta_imagen_usuario,
                u.ruta_imagen_portada,
                c.perfil_postulante_curriculum,
                car.nombre_carrera
            FROM postulante p
            LEFT JOIN sexo s ON p.id_sexo = s.id_sexo
            LEFT JOIN estado_postulante ep ON p.id_estado_postulante = ep.id_estado_postulante
            LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
            LEFT JOIN curriculum c ON p.id_curriculum = c.id_curriculum
            LEFT JOIN carrera car ON c.id_carrera = car.id_carrera
            WHERE p.id_postulante = ?";
    
    // Prepare the query
    $stmt = mysqli_prepare($cn, $sql);
    
    if (!$stmt) {
        // Handle query preparation errors
        error_log("Error preparing query: " . mysqli_error($cn));
        return null;
    }
    
    // Bind the id_postulante parameter
    mysqli_stmt_bind_param($stmt, "i", $id_postulante);
    
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
    
    // Fetch the postulante data
    $datosPostulante = mysqli_fetch_assoc($resultado);
    
    // Close the prepared statement
    mysqli_stmt_close($stmt);
    
    // Return the postulante data or null if no data found
    return $datosPostulante ? $datosPostulante : null;
}