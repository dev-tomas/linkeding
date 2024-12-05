<?php
include("../sections/conexion.php"); // Incluimos el archivo de conexión

// ID de la propuesta que quieres consultar
$cod = $_SESSION['id_usuario']; // Obtén el ID de la propuesta desde el parámetro GET

$r = []; // Inicializamos la variable $r como un array vacío

// Verifica que $cod no esté vacío
if (!empty($cod)) {
    // Consulta SQL para obtener los datos de las tablas relacionadas
    $sql = "SELECT p.id_propuesta, 
                   p.nombre_propuesta, 
                   p.descripcion_propuesta, 
                   p.requisitos_propuesta, 
                   p.fecha_limite, 
                   ep.nombre_estado_propuesta
            FROM propuesta p
            INNER JOIN estado_propuesta ep ON p.id_estado_propuesta = ep.id_estado_propuesta
            WHERE p.id_propuesta = $cod";

    $f = mysqli_query($cn, $sql); // Ejecutamos la consulta
    if ($f && mysqli_num_rows($f) > 0) {
        $r = mysqli_fetch_assoc($f); // Asociamos el resultado
    } else {
        echo "No se encontraron datos para la propuesta seleccionada.";
    }
} else {
    echo "El código de propuesta no fue proporcionado.";
}
?>
