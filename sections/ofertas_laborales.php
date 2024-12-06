<?php 
require_once __DIR__.'/../control/p_ofertas_laborales.php';
include __DIR__ .'/../control/p_imagen_empresa.php'; 
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
<Center><H2>OFERTAS LABORALES</H2></Center>
    <table class="offer-table">
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
                    <div class="onlynow-container">
                        <?php
                        $ruta_imagen_empresa = obtenerImagenEmpresa($r['id_empresa']);
                        ?>
                        <img src="<?php echo htmlspecialchars($ruta_imagen_empresa); ?>?<?php echo time(); ?>"
                             alt="Foto de empresa" class="profile-image">
                    </div>
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
