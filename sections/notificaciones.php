<?php
include("conexion.php");
include __DIR__ . '/../control/p_imagen_empresa.php'; 

$postulante = $_SESSION['usuario_id'];

// PAGINACIÓN
$NumeroNotificaciones = 3;
$PaginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$Orden = ($PaginaActual - 1) * $NumeroNotificaciones;

// CONSULTA PARA TRAER LOS DATOS PARA HACER PAGINACIÓN
$Mensaje = "select COUNT(*) AS total
    FROM notificacion n
    INNER JOIN mensaje m ON n.id_mensaje = m.id_mensaje
    WHERE m.id_usuario_receptor_mensaje = ?";

$stmtTotal = $cn->prepare($Mensaje);
$stmtTotal->bind_param("i", $postulante);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$TotalNotificaciones = $resultTotal->fetch_assoc()['total'];
$stmtTotal->close();

// TOTAL NÚMERO DE PÁGINAS
$TotalPaginas = ceil($TotalNotificaciones / $NumeroNotificaciones);

// Obtener las notificaciones para la página actual
$sql = "select 
        n.id_notificacion,
        m.id_mensaje, 
        m.mensaje, 
        m.fecha_mensaje, 
        e.nombre_estado_mensaje AS estado, 
        p.nombre_propuesta,
        emp.razon_social_empresa AS nombre_emisor, 
        p.descripcion_propuesta,
        emp.id_empresa
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
$stmt->bind_param("iii", $postulante, $NumeroNotificaciones, $Orden);
$stmt->execute();
$result = $stmt->get_result();

$notificaciones = [];
while ($row = $result->fetch_assoc()) {
    $notificaciones[] = $row;
}
$stmt->close();

// Marcar como leído (cuando se hace clic en "Ver Mensaje")
if (isset($_POST['mensaje_id'])) {
    $mensaje_id = $_POST['mensaje_id'];

    // Actualizar el estado de la notificación a "leído"
    $updateQuery = "UPDATE mensaje SET id_estado_mensaje = (SELECT id_estado_mensaje FROM estado_mensaje WHERE nombre_estado_mensaje = 'leído') WHERE id_mensaje = ?";
    $stmtUpdate = $cn->prepare($updateQuery);
    $stmtUpdate->bind_param("i", $mensaje_id);
    $stmtUpdate->execute();
    $stmtUpdate->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/notificaciones_postulante.css">
</head>
<body>
<?php
if($TotalPaginas>0){
?>    
<php?
    <div class="contenido">
        <?php if (empty($notificaciones)): ?>
            <p>No tienes notificaciones.</p>
        <?php else: ?>
            <?php foreach ($notificaciones as $notificacion): ?>
                <div class="notificacion-container">
                    <!-- Imagen de la empresa -->
                    <?php
                    if (isset($notificacion['id_empresa'])) {
                        $ruta_imagen_empresa = obtenerImagenEmpresa($notificacion['id_empresa']);
                    } 
                    ?>
                    <div class="onlynow-container">
                        <img src="<?php echo htmlspecialchars($ruta_imagen_empresa); ?>?<?php echo time(); ?>"
                             alt="Foto de empresa" class="profile-image">
                    </div>

                    <!-- Notificación -->
                    <div class="notificacion <?= $notificacion['estado'] === 'leído' ? 'leido' : 'no-leido'; ?>" id="notificacion-<?= $notificacion['id_mensaje']; ?>">
                        <div class="info">
                            <p><strong>Mensaje de:</strong> <?= htmlspecialchars($notificacion['nombre_emisor'] ?? 'Desconocido'); ?></p>
                            <p><strong>Fecha:</strong> <?= htmlspecialchars($notificacion['fecha_mensaje']); ?></p>
                            <?php if (!empty($notificacion['nombre_propuesta'])): ?>
                                <p><strong>Propuesta:</strong> <?= htmlspecialchars($notificacion['nombre_propuesta']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="boton">
                            <button 
                                class="btn btn-info btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#mensajeModal<?= $notificacion['id_mensaje']; ?>"
                                onclick="marcarComoLeido(<?= $notificacion['id_mensaje']; ?>)">
                                Ver Mensaje
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal para mostrar el mensaje -->
                <div class="modal fade" id="mensajeModal<?= $notificacion['id_mensaje']; ?>" tabindex="-1" aria-labelledby="mensajeModalLabel<?= $notificacion['id_mensaje']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="mensajeModalLabel<?= $notificacion['id_mensaje']; ?>">Mensaje</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>De:</strong> <?= htmlspecialchars($notificacion['nombre_emisor'] ?? 'Desconocido'); ?></p>
                                <p><strong>Fecha:</strong> <?= htmlspecialchars($notificacion['fecha_mensaje']); ?></p>
                                <p><?= nl2br(htmlspecialchars($notificacion['mensaje'])); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="paginacion">
        <nav>
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?= ($PaginaActual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=notificaciones&pagina=<?= $PaginaActual - 1; ?>" <?= ($PaginaActual <= 1) ? 'tabindex="-1"' : ''; ?>>Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $TotalPaginas; $i++): ?>
                    <li class="page-item <?= $i == $PaginaActual ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?page=notificaciones&pagina=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($PaginaActual >= $TotalPaginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=notificaciones&pagina=<?= $PaginaActual + 1; ?>" <?= ($PaginaActual >= $TotalPaginas) ? 'tabindex="-1"' : ''; ?>>Siguiente</a>
                </li>
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


<?php
}else{
    ?>

<center><h2>No hay notificaciones</h2></center>

<?php
}?>
</body>
</html>

