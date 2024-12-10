<?php
include("conexion.php");

$registros_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Verificar si se hizo clic en "Desactivar Postulante"
if (isset($_GET['desactivar_postulante']) && isset($_GET['id_usuario_queja'])) {
    $id_usuario_queja = intval($_GET['id_usuario_queja']);

    // Cambiar estado del postulante (usuario_queja) a inactivo (estado 0)
    $query_actualizar_estado = "UPDATE usuario SET estado_usuario = 0 WHERE id_usuario = $id_usuario_queja";
    mysqli_query($cn, $query_actualizar_estado);

    // Redirigir para evitar bucle en el parámetro
    header("Location: index.php?page=atencion_postulante");
    exit();
}

// Verificar si se hizo clic en cambiar estado del postulante
if (isset($_GET['cambiar_estado_postulante']) && isset($_GET['id_postulante'])) {
    $id_postulante = intval($_GET['id_postulante']);

    // Obtener el estado actual
    $query_estado_actual = "SELECT id_estado_postulante FROM postulante WHERE id_postulante = $id_postulante";
    $resultado = mysqli_query($cn, $query_estado_actual);
    $estado_actual = mysqli_fetch_assoc($resultado)['id_estado_postulante'];

    // Cambiar entre estado 1 y 2
    $nuevo_estado = ($estado_actual == 1) ? 2 : 1;

    // Actualizar estado del postulante
    $query_actualizar_estado = "UPDATE postulante SET id_estado_postulante = $nuevo_estado WHERE id_postulante = $id_postulante";
    mysqli_query($cn, $query_actualizar_estado);

    // Redirigir
    header("Location: index.php?page=atencion_postulante");
    exit();
}

// Consultar total de registros para la paginación
$sql_total = "SELECT COUNT(*) as total 
              FROM queja q
              JOIN mensaje m ON q.id_mensaje = m.id_mensaje
              JOIN usuario u ON q.id_usuario_queja = u.id_usuario";
$resultado_total = mysqli_query($cn, $sql_total);
$total_registros = mysqli_fetch_assoc($resultado_total)['total'];

$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consultar los datos paginados con información adicional del postulante
$sql = "SELECT q.id_queja, 
               q.id_usuario_queja, 
               p.id_postulante,
               p.id_estado_postulante,
               m.id_mensaje, 
               m.mensaje, 
               (SELECT e.razon_social_empresa FROM empresa e WHERE e.id_usuario = m.id_usuario_emisor_mensaje) AS emisor_empresa,
               (SELECT CONCAT(nombre_administrador, ' ', apellido_paterno_administrador, ' ', apellido_materno_administrador) 
                FROM administrador a 
                JOIN usuario u ON a.id_usuario = u.id_usuario 
                WHERE u.id_usuario = m.id_usuario_receptor_mensaje) AS receptor_nombre_completo,
               (SELECT CONCAT(nombre_postulante, ' ', apellido_paterno_postulante, ' ', apellido_materno_postulante) 
                FROM postulante 
                WHERE id_usuario = q.id_usuario_queja) AS nombre_postulante_completo,
               (SELECT nombre_estado_postulante FROM estado_postulante WHERE id_estado_postulante = p.id_estado_postulante) AS estado_postulante
        FROM queja q
        JOIN mensaje m ON q.id_mensaje = m.id_mensaje
        JOIN postulante p ON q.id_usuario_queja = p.id_usuario
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
                    <th>Emisor (Empresa)</th>
                    <th>Receptor (Administrador)</th>
                    <th>Postulante Acusado</th>
                    <th>Estado Actual</th>
                    <th><center>Acciones</center></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($queja = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td>
                        <button class="btn btn-info btn-sm ver-mensaje"
                            data-mensaje="<?php echo htmlspecialchars($queja['mensaje']); ?>"
                            data-emisor="<?php echo htmlspecialchars($queja['emisor_empresa']); ?>"
                            data-receptor="<?php echo htmlspecialchars($queja['receptor_nombre_completo']); ?>"
                            data-id-mensaje="<?php echo $queja['id_mensaje']; ?>">
                            Ver Mensaje
                        </button>
                    </td>
                    <td><?php echo htmlspecialchars($queja['emisor_empresa']); ?></td>
                    <td><?php echo htmlspecialchars($queja['receptor_nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($queja['nombre_postulante_completo']); ?></td>
                    <td><?php echo htmlspecialchars($queja['estado_postulante']); ?></td>
                    <td><center>
                        <div class="btn-group" role="group">
                            <a href="index.php?page=atencion_postulante&id_postulante=<?php echo $queja['id_postulante']; ?>&cambiar_estado_postulante=1"
                                class="btn btn-warning btn-sm">
                                Cambiar Estado
                            </a>
                            </center>
                        </div>
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
                        <a class="page-link"
                            href="index.php?page=atencion_postulante&pagina=<?php echo $pagina_actual - 1; ?>">Anterior</a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="index.php?page=atencion_postulante&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="index.php?page=atencion_postulante&pagina=<?php echo $pagina_actual + 1; ?>">Siguiente</a>
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

            $('#modalEmisor').text(emisor);
            $('#modalReceptor').text(receptor);
            $('#modalMensaje').text(mensaje);

            var mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
            mensajeModal.show();
        });
    });
    </script>
</body>

</html>