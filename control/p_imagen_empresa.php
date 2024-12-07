<?php
require_once 'sections/conexion.php'; // Conexión a la base de datos

function obtenerImagenEmpresa($id_empresa) {
    global $cn; // Usar la conexión global a la base de datos desde conexion.php

    $sql = "SELECT u.ruta_imagen_usuario
            FROM empresa e
            JOIN usuario u ON e.id_usuario = u.id_usuario 
            WHERE e.id_empresa = ?";

    // Preparar la consulta
    $stmt = mysqli_prepare($cn, $sql);

    if (!$stmt) {
        // Manejar errores en la preparación
        error_log("Error al preparar la consulta: " . mysqli_error($cn));
        return '../img/user.svg'; // Imagen por defecto
    }

    // Vincular el parámetro id_empresa
    mysqli_stmt_bind_param($stmt, "i", $id_empresa);

    // Ejecutar la consulta
    if (!mysqli_stmt_execute($stmt)) {
        // Manejar errores en la ejecución
        error_log("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return '../img/user.svg'; // Imagen por defecto
    }

    // Obtener el resultado de la consulta
    $resultado = mysqli_stmt_get_result($stmt);

    if (!$resultado) {
        // Manejar errores en la obtención de resultados
        error_log("Error al obtener resultados: " . mysqli_error($cn));
        mysqli_stmt_close($stmt);
        return '../img/user.svg'; // Imagen por defecto
    }

    // Obtener la fila con la ruta de la imagen
    $fila = mysqli_fetch_assoc($resultado);

    // Cerrar la consulta preparada
    mysqli_stmt_close($stmt);

    // Retornar la ruta de la imagen o una imagen por defecto si está vacía o nula
    return (!empty($fila['ruta_imagen_usuario'])) 
        ? '../img/usuario/' . $fila['ruta_imagen_usuario'] 
        : '../img/user.svg';
}
?>
