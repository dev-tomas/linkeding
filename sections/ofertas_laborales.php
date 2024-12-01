<?php
include("conexion.php");



$sql = "select
        e.razon_social_empresa AS nombre_empresa, 
        p.nombre_propuesta AS propuesta
    from 
        detalle_empresa_propuesta dep
    inner join 
        empresa e ON dep.id_empresa = e.id_empresa
    inner join 
        propuesta p ON dep.id_propuesta = p.id_propuesta";

$fila = mysqli_query($cn, $sql);

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Oferta</title>
</head>
<body>
    <center><h1>OFERTAS LABORALES</h1></center>

    <table border="1">
        <tr>
            <td><strong>EMPRESA</strong></td>
            <td><strong>FOTO</strong></td>
            <td><strong>PROPUESTA</strong></td>
            <td><strong>VER PROPUESTA</strong></td>
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
