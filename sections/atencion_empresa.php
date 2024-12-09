<?php
include("conexion.php");

$registros_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

$sql_total = "SELECT COUNT(*) as total FROM empresa e 
        JOIN estado_empresa es ON e.id_estado_empresa = es.id_estado_empresa
        JOIN queja q on q.id_usuario_queja = e.id_usuario
        JOIN mensaje m ON m.id_mensaje = q.id_mensaje
        JOIN estado_mensaje eq ON eq.id_estado_mensaje = m.id_estado_mensaje";
$resultado_total = mysqli_query($cn, $sql_total);
$total_registros = mysqli_fetch_assoc($resultado_total)['total'];

$total_paginas = ceil($total_registros / $registros_por_pagina);

$sql = "SELECT e.id_empresa, e.ruc_empresa, e.razon_social_empresa, e.celular_empresa, e.direccion_empresa, 
               e.representante_empresa, es.nombre_estado_empresa, m.id_mensaje, m.mensaje, eq.id_estado_mensaje, eq.nombre_estado_mensaje,
               (SELECT u.nombre_usuario FROM usuario u WHERE u.id_usuario = m.id_usuario_emisor_mensaje) AS emisor
        FROM empresa e 
        JOIN estado_empresa es ON e.id_estado_empresa = es.id_estado_empresa
        JOIN queja q on q.id_usuario_queja = e.id_usuario
        JOIN mensaje m ON m.id_mensaje = q.id_mensaje
        JOIN estado_mensaje eq ON eq.id_estado_mensaje = m.id_estado_mensaje
        LIMIT $registros_por_pagina OFFSET $offset";

$resultado = mysqli_query($cn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atención a Empresas - Quejas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Quejas de Empresas</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Emisor</th>
            <th>Empresa</th>
            <th>RUC</th>
            <th>Celular</th>
            <th>Representante</th>
            <th>Estado</th>
            <th>Comentario</th>
            <th>Check</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($empresa = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo $empresa['emisor']; ?></td>
                <td><?php echo $empresa['razon_social_empresa']; ?></td>
                <td><?php echo $empresa['ruc_empresa']; ?></td>
                <td><?php echo $empresa['celular_empresa']; ?></td>
                <td><?php echo $empresa['representante_empresa']; ?></td>
                <td><?php echo $empresa['nombre_estado_empresa']; ?></td>
                <td><?php echo $empresa['mensaje']; ?></td>
                <td>
                    <?php if ($empresa['nombre_estado_mensaje'] === 'enviado') { ?>
                        <form action="sections/p_leer_comen_emp.php" method="POST">
                            <input type="hidden" name="id_comentario" value="<?php echo $empresa['id_mensaje']; ?>">
                            <button type="submit" class="btn-leer">Marcar como leído</button>
                        </form>
                    <?php } else { ?>
                        <span class="text-muted">No disponible</span>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($empresa['nombre_estado_mensaje'] === 'enviado') { ?>
                        <form action="sections/p_leer_comen_emp.php" method="POST">
                            <input type="hidden" name="id_comentario" value="<?php echo $empresa['id_mensaje']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Marcar como leído</button>
                        </form>
                    <?php } ?>
                    
                    <?php if ($empresa['nombre_estado_empresa'] == 'Inactivo') { ?>
                        <button class="btn btn-success btn-sm cambiar-estado mt-2" 
                                data-id="<?php echo $empresa['id_empresa']; ?>" 
                                data-estado="Activo">Activar</button>
                    <?php } else { ?>
                        <button class="btn btn-warning btn-sm cambiar-estado mt-2" 
                                data-id="<?php echo $empresa['id_empresa']; ?>" 
                                data-estado="Inactivo">Cambiar a Inactivo</button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                <?php if ($pagina_actual > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=atencion_empresa&pagina=<?php echo $pagina_actual - 1; ?>">Anterior</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?page=atencion_empresa&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=atencion_empresa&pagina=<?php echo $pagina_actual + 1; ?>">Siguiente</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<script>
$(document).on('click', '.cambiar-estado', function() {
    let idEmpresa = $(this).data('id');
    let nuevoEstado = $(this).data('estado');

    if (confirm(`¿Estás seguro de que deseas cambiar el estado a '${nuevoEstado}'?`)) {
        $.ajax({
            url: 'sections/cambiar_estado_empresa.php',
            type: 'POST',
            data: { id_empresa: idEmpresa, nuevo_estado: nuevoEstado },
            success: function(response) {
                let res = JSON.parse(response);
                if (res.success) {
                    alert(res.message);
                    location.reload(); 
                } else {
                    alert(res.message);
                }
            }
        });
    }
});
</script>
</body>
</html>