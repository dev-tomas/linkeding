<?php
include("sections/conexion.php");

// Obtén el ID de la propuesta desde la URLs
$id_propuesta = isset($_GET['id']) ? intval($_GET['id']) : 0;


// Consulta para obtener los postulantes de la propuesta
$sql = "
    SELECT 
    p.id_postulante,
    p.nombre_postulante, 
    p.apellido_paterno_postulante, 
    p.apellido_materno_postulante, 
    p.celular_postulante, 
    p.direccion_postulante, 
    p.fecha_nacimiento_postulante,
    c.nombre_carrera AS profesion_postulante, 
    cu.ruta_curriculum,
    dp.fecha_postulacion
FROM detalle_postulante_propuesta dp
INNER JOIN postulante p ON dp.id_postulante = p.id_postulante
LEFT JOIN curriculum cu ON cu.id_curriculum = p.id_curriculum
LEFT JOIN carrera c ON c.id_carrera = cu.id_carrera
WHERE dp.id_propuesta = $id_propuesta;
";

$resultado = mysqli_query($cn, $sql);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($cn));
}
?>