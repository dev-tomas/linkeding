<?php
// notificaciones.php
include("conexion.php");

$postulante = $_SESSION['usuario_id']; 

// Parámetros para la paginación
$porPagina = 3; // Número de notificaciones por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $porPagina;

// Contar el total de notificaciones para calcular las páginas
$sqlTotal = "
    SELECT COUNT(*) AS total
    FROM notificacion n
    INNER JOIN mensaje m ON n.id_mensaje = m.id_mensaje
    WHERE m.id_usuario_receptor_mensaje = ?";
$stmtTotal = $cn->prepare($sqlTotal);
$stmtTotal->bind_param("i", $postulante);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$totalNotificaciones = $resultTotal->fetch_assoc()['total'];
$stmtTotal->close();

// Calcular el número total de páginas
$totalPaginas = ceil($totalNotificaciones / $porPagina);

// Obtener las notificaciones con límite para la página actual
$sql = "
    SELECT 
        n.id_notificacion,
        m.id_mensaje, 
        m.mensaje, 
        m.fecha_mensaje, 
        e.nombre_estado_mensaje AS estado, 
        p.nombre_propuesta,
        emp.razon_social_empresa AS nombre_emisor, 
        p.descripcion_propuesta
    FROM notificacion n
    INNER JOIN mensaje m ON n.id_mensaje = m.id_mensaje
    INNER JOIN estado_mensaje e ON m.id_estado_mensaje = e.id_estado_mensaje
    LEFT JOIN propuesta p ON n.id_propuesta = p.id_propuesta
    INNER JOIN usuario u_emisor ON m.id_usuario_emisor_mensaje = u_emisor.id_usuario
    INNER JOIN empresa emp ON u_emisor.id_usuario = emp.id_usuario
    WHERE m.id_usuario_receptor_mensaje = ?
    ORDER BY m.fecha_mensaje DESC
    LIMIT ? OFFSET ?";
$stmt = $cn->prepare($sql);
$stmt->bind_param("iii", $postulante, $porPagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

$notificaciones = [];
while ($row = $result->fetch_assoc()) {
    $notificaciones[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Notificaciones</h1>
        <?php if (empty($notificaciones)): ?>
            <p>No tienes notificaciones.</p>
        <?php else: ?>
            <?php foreach ($notificaciones as $notificacion): ?>
                <div class="notificacion <?= $notificacion['estado'] === 'leído' ? 'leido' : 'no-leido'; ?> mb-3" id="notificacion-<?= $notificacion['id_mensaje']; ?>">
                    <p><strong>Mensaje de:</strong> <?= htmlspecialchars($notificacion['nombre_emisor'] ?? 'Desconocido'); ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($notificacion['fecha_mensaje']); ?></p>
                    
                    <?php if (!empty($notificacion['nombre_propuesta'])): ?>
                        <p><strong>Propuesta:</strong> <?= htmlspecialchars($notificacion['nombre_propuesta']); ?></p>
                    <?php endif; ?>

                    <!-- Botón para abrir el modal -->
                    <button 
                        class="btn btn-info btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#mensajeModal<?= $notificacion['id_mensaje']; ?>"
                        onclick="marcarComoLeido(<?= $notificacion['id_mensaje']; ?>)">
                        Ver Mensaje
                    </button>

                    <!-- Modal del mensaje -->
                    <div 
                        class="modal fade" 
                        id="mensajeModal<?= $notificacion['id_mensaje']; ?>" 
                        tabindex="-1" 
                        aria-labelledby="mensajeModalLabel<?= $notificacion['id_mensaje']; ?>" 
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="mensajeModalLabel<?= $notificacion['id_mensaje']; ?>">
                                        Mensaje de <?= htmlspecialchars($notificacion['nombre_emisor'] ?? 'Desconocido'); ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <?php if (!empty($notificacion['nombre_propuesta'])): ?>
                                        <p><strong>Propuesta:</strong> <?= htmlspecialchars($notificacion['nombre_propuesta']); ?></p>
                                        <p><strong>Descripción:</strong> <?= htmlspecialchars($notificacion['descripcion_propuesta']); ?></p>
                                    <?php endif; ?>
                                    
                                    <p><strong>Mensaje:</strong></p>
                                    <p><?= htmlspecialchars($notificacion['mensaje']); ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Paginación -->
        <nav>
            <ul class="pagination justify-content-center mt-4">
                <?php if ($paginaActual > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=notificaciones&pagina=<?= $paginaActual - 1; ?>">Anterior</a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i == $paginaActual ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?page=notificaciones&pagina=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($paginaActual < $totalPaginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=notificaciones&pagina=<?= $paginaActual + 1; ?>">Siguiente</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

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
</body>
</html>








