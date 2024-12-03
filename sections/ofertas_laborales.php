<?php
include("conexion.php");

$sql = "
SELECT 
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Oferta</title>
    <link rel="stylesheet" href="../css/ofertas_laborales.css">
</head>
<body>
<br>
    <table border="1" class="offer-table">
        <tr>
            <td class="header-column"><strong>EMPRESA</strong></td>
            <td class="header-column"><strong>FOTO</strong></td>
            <td class="header-column"><strong>PROPUESTA</strong></td>
            <td class="header-column"><strong>VER PROPUESTA</strong></td>
        </tr>

        <?php 
        
        while ($r = mysqli_fetch_assoc($fila)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($r['nombre_empresa']); ?></td>

                <td>
                    FOTO
                </td>
                
                <td><?php echo htmlspecialchars($r['propuesta']); ?></td>
                
                <td>
                <a href="index.php?page=ver_oferta&empresa=<?php echo urlencode(trim($r['nombre_empresa'])); ?>&propuesta=<?php echo urlencode(trim($r['propuesta'])); ?>">Ver Propuesta</a>
                </td>
            </tr>

        <?php 
        } 
        ?>

    </table>
</body>
</html>
