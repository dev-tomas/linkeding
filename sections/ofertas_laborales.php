<?php
require_once __DIR__ . '/../control/p_ofertas_laborales.php';
include __DIR__ . '/../control/p_imagen_empresa.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Oferta</title>  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/ofertas_laborales.css">
</head>

<body>
    <Center>
        <H2>OFERTAS LABORALES</H2>
    </Center>
    <div class="contenido">
    
    <table class="offer-table">
        <tr>
            <td class="header-column"><strong>FOTO</strong></td>
            <td class="header-column"><strong>EMPRESA</strong></td>
            <td class="header-column"><strong>PROPUESTA</strong></td>
            <td class="header-column"><strong>VER PROPUESTA</strong></td>
        </tr>

        <?php
        while ($r = mysqli_fetch_assoc($fila)) { ?>
            <tr>
                <td>
                    <div class="onlynow-container">
                        <a href="index.php?page=ver_home&id_empresa=<?php echo $r['id_empresa']; ?>">
                            <?php
                            $ruta_imagen_empresa = obtenerImagenEmpresa($r['id_empresa']);
                            ?>
                            <img src="<?php echo htmlspecialchars($ruta_imagen_empresa); ?>?<?php echo time(); ?>"
                                alt="Foto de empresa" class="profile-image">
                        </a>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($r['nombre_empresa']); ?></td>


                <td><?php echo htmlspecialchars($r['propuesta']); ?></td>

                <td>
                    <a
                        href="index.php?page=ver_oferta&empresa=<?php echo urlencode(trim($r['nombre_empresa'])); ?>&propuesta=<?php echo urlencode(trim($r['propuesta'])); ?>">Ver
                        Propuesta</a>
                </td>
            </tr>

        <?php
        }
        ?>

    </table>
    </div>
    
    <div class="paginacion">
        <nav>
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?= ($PaginaActual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=ofertas_laborales&pagina=<?= $PaginaActual - 1; ?>" <?= ($PaginaActual <= 1) ? 'tabindex="-1"' : ''; ?>>Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $TotalPaginas; $i++): ?>
                    <li class="page-item <?= $i == $PaginaActual ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?page=ofertas_laborales&pagina=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($PaginaActual >= $TotalPaginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=ofertas_laborales&pagina=<?= $PaginaActual + 1; ?>" <?= ($PaginaActual >= $TotalPaginas) ? 'tabindex="-1"' : ''; ?>>Siguiente</a>
                </li>
            </ul>
        </nav>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function marcarComoLeido(mensajeId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var notificacion = document.getElementById('notificacion-' + mensajeId);
                    notificacion.classList.remove('no-leido');
                    notificacion.classList.add('leido');
                }
            };
            xhr.send('mensaje_id=' + mensajeId);
        }
    </script>
</div>
</body>

</html>