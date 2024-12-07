<?php
require_once 'sections/conexion.php'; // Use your existing connection file

function obtenerImagenPostulante($id_postulante) {
    global $cn; // Use the global database connection variable from conexion.php

    $sql = "SELECT u.ruta_imagen_usuario 
            FROM postulante p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE p.id_postulante = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($cn, $sql);

    if (!$stmt) {
        // Handle preparation error
        error_log("Prepared statement error: " . mysqli_error($cn));
        return 'img/user.svg';
    }

    // Bind the postulante ID parameter
    mysqli_stmt_bind_param($stmt, "i", $id_postulante);

    // Execute the statement
    if (!mysqli_stmt_execute($stmt)) {
        // Handle execution error
        error_log("Statement execution error: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return 'img/user.svg';
    }

    // Get the result
    $resultado = mysqli_stmt_get_result($stmt);

    if (!$resultado) {
        // Handle result error
        error_log("Result error: " . mysqli_error($cn));
        mysqli_stmt_close($stmt);
        return 'img/user.svg';
    }

    // Fetch the image path
    $fila = mysqli_fetch_assoc($resultado);
    
    // Close the statement
    mysqli_stmt_close($stmt);

    // Return the image path, or a default image if null or empty
    return (!empty($fila['ruta_imagen_usuario'])) 
        ? 'img/usuario/' . $fila['ruta_imagen_usuario'] 
        : 'img/user.svg';
}
?>