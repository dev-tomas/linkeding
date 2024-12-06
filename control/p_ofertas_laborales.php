<?php
include("sections/conexion.php");
$sql = "
SELECT 
    e.id_empresa,
    e.razon_social_empresa AS nombre_empresa, 
    p.nombre_propuesta AS propuesta,
    ep.nombre_estado_propuesta
FROM 
    detalle_empresa_propuesta dep
INNER JOIN 
    empresa e ON dep.id_empresa = e.id_empresa
INNER JOIN 
    propuesta p ON dep.id_propuesta = p.id_propuesta
INNER JOIN 
    estado_propuesta ep ON p.id_estado_propuesta = ep.id_estado_propuesta
WHERE
    ep.nombre_estado_propuesta IN ('activo','expirado');

";

$fila = mysqli_query($cn, $sql);

?>