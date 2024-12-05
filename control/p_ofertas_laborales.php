<?php
include("sections/conexion.php");
$sql = "
SELECT 
    e.id_empresa,
    e.razon_social_empresa AS nombre_empresa, 
    p.nombre_propuesta AS propuesta
FROM 
    detalle_empresa_propuesta dep
INNER JOIN 
    empresa e ON dep.id_empresa = e.id_empresa
INNER JOIN 
    propuesta p ON dep.id_propuesta = p.id_propuesta
GROUP BY 
    e.id_empresa, p.id_propuesta
";

$fila = mysqli_query($cn, $sql);

?>