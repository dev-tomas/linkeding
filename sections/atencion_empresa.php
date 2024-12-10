<?php
include("conexion.php");

$registros_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Verificar si se hizo clic específicamente en Cambiar estado
if (isset($_GET['cambiar_estado']) && isset($_GET['id_empresa'])) {
    $id_empresa = intval($_GET['id_empresa']);

    // Consulta para obtener el estado actual de la empresa
    $query_estado_actual = "SELECT id_estado_empresa FROM empresa WHERE id_empresa = $id_empresa";
    $resultado_estado = mysqli_query($cn, $query_estado_actual);
    
    if ($resultado_estado) {
        $fila = mysqli_fetch_assoc($resultado_estado);
        $estado_actual = $fila['id_estado_empresa'];
        
        // Cambiar el estado (de 1 a 2 o de 2 a 1)
        $nuevo_estado = ($estado_actual == 1) ? 2 : 1;
        
        // Consulta para actualizar el estado
        $query_actualizar = "UPDATE empresa SET id_estado_empresa = $nuevo_estado WHERE id_empresa = $id_empresa";
        mysqli_query($cn, $query_actualizar);
    }

    // Redirigir sin el parámetro cambiar_estado para evitar bucle
    header("Location: index.php?page=atencion_empresa");
    exit();
}

$sql_total = "SELECT COUNT(*) as total FROM empresa e 
        JOIN estado_empresa es ON e.id_estado_empresa = es.id_estado_empresa
        JOIN queja q on q.id_usuario_queja = e.id_usuario
        JOIN mensaje m ON m.id_mensaje = q.id_mensaje
        JOIN estado_mensaje eq ON eq.id_estado_mensaje = m.id_estado_mensaje";
$resultado_total = mysqli_query($cn, $sql_total);
$total_registros = mysqli_fetch_assoc($resultado_total)['total'];

$total_paginas = ceil($total_registros / $registros_por_pagina);

$sql = "SELECT e.id_empresa, e.id_estado_empresa,e.ruc_empresa, e.razon_social_empresa, e.celular_empresa, e.direccion_empresa, 
               e.representante_empresa, es.nombre_estado_empresa, m.id_mensaje, m.mensaje, eq.id_estado_mensaje, eq.nombre_estado_mensaje,
               (SELECT u.nombre_usuario FROM usuario u WHERE u.id_usuario = m.id_usuario_emisor_mensaje) AS emisor,
               (SELECT u.nombre_usuario FROM usuario u WHERE u.id_usuario = m.id_usuario_receptor_mensaje) AS receptor
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
                    <th>Ver Mensaje</th>
                    <th>Emisor</th>
                    <th>Empresa reportada</th>
                    <th>RUC</th>
                    <th>Celular de la empresa</th>
                    <th>Representante</th>
                    <th>Estado</th>
                    <th><center>Cambiar estado</center></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($empresa = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td>
                        <button class="btn btn-info btn-sm ver-mensaje"
                            data-mensaje="<?php echo htmlspecialchars($empresa['mensaje']); ?>"
                            data-emisor="<?php echo htmlspecialchars($empresa['emisor']); ?>"
                            data-receptor="<?php echo htmlspecialchars($empresa['receptor']); ?>"
                            data-id-mensaje="<?php echo $empresa['id_mensaje']; ?>">
                            Ver Mensaje
                        </button>
                    </td>
                    <td><?php echo htmlspecialchars($empresa['emisor']); ?></td>
                    <td><?php echo htmlspecialchars($empresa['razon_social_empresa']); ?></td>
                    <td><?php echo $empresa['ruc_empresa']; ?></td>
                    <td><?php echo $empresa['celular_empresa']; ?></td>
                    <td><?php echo htmlspecialchars($empresa['representante_empresa']); ?></td>
                    <td><?php echo htmlspecialchars($empresa['nombre_estado_empresa']); ?></td>
                    <td><center>
                        <a href="index.php?page=atencion_empresa&id_empresa=<?php echo $empresa['id_empresa']; ?>&cambiar_estado=1"
                            class="btn <?php echo ($empresa['id_estado_empresa'] == 1) ? 'btn-success' : 'btn-danger'; ?> btn-sm">
                            <?php echo ($empresa['id_estado_empresa'] == 1) ? 'Activo' : 'Inactivo'; ?>
                        </a>
                        </center></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    <?php if ($pagina_actual > 1): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="index.php?page=atencion_empresa&pagina=<?php echo $pagina_actual - 1; ?>">Anterior</a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="index.php?page=atencion_empresa&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="index.php?page=atencion_empresa&pagina=<?php echo $pagina_actual + 1; ?>">Siguiente</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal para mostrar mensaje -->
    <div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mensajeModalLabel">Detalles del Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Emisor:</strong> <span id="modalEmisor"></span></p>
                    <p><strong>Administrador:</strong> <span id="modalReceptor"></span></p>
                    <p><strong>Mensaje:</strong> <span id="modalMensaje"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.ver-mensaje').on('click', function() {
            let mensaje = $(this).data('mensaje');
            let emisor = $(this).data('emisor');
            let receptor = $(this).data('receptor');
            let idMensaje = $(this).data('id-mensaje');

            $('#modalEmisor').text(emisor);
            $('#modalReceptor').text(receptor);
            $('#modalMensaje').text(mensaje);

            // Mostrar modal
            var mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
            mensajeModal.show();

            // Cambiar estado del mensaje automáticamente
            $.ajax({
                url: 'sections/cambiar_estado_mensaje.php',
                type: 'POST',
                data: {
                    id_mensaje: idMensaje
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        // Recargar la página después de cambiar el estado
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    });
    </script>
</body>

</html>