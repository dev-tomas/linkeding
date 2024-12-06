<?php
include("conexion.php");

$postulante = $_SESSION['usuario_id']; 

// Verificar si se recibió un mensaje para marcarlo como leído
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mensaje_id'])) {
    $mensajeId = $_POST['mensaje_id'];
    
    // Actualizar el estado del mensaje a "leído"
    $sqli= "UPDATE mensaje SET id_estado_mensaje = (SELECT id_estado_mensaje FROM estado_queja WHERE nombre_estado_mensaje = 'leído') WHERE id_mensaje = ? AND id_usuario_receptor_mensaje = ?";
    $stmt = $cn->prepare($sqli);
    $stmt->bind_param("ii", $mensajeId, $postulante);
    $stmt->execute();
    $stmt->close();
    
    // Enviar respuesta para indicar que se actualizó correctamente
    echo 'ok';
    exit;
}

// Obtener las notificaciones con el mensaje completo
$sql = "
    SELECT
        m.id_mensaje, 
        m.mensaje, 
        m.fecha_mensaje, 
        e.nombre_estado_mensaje AS estado, 
        t.nombre_tipo_mensaje AS tipo,
        emp.razon_social_empresa AS empresa_emisora
    FROM mensaje m
    INNER JOIN estado_queja e ON m.id_estado_mensaje = e.id_estado_mensaje
    INNER JOIN tipo_mensaje t ON m.id_tipo_mensaje = t.id_tipo_mensaje
    INNER JOIN usuario u ON m.id_usuario_emisor_mensaje = u.id_usuario
    INNER JOIN empresa emp ON u.id_usuario = emp.id_usuario
    WHERE m.id_usuario_receptor_mensaje = ? 
    ORDER BY m.fecha_mensaje DESC";

$fila = $cn->prepare($sql);
$fila->bind_param("i", $postulante);
$fila->execute();
$r = $fila->get_result();

$notificaciones = [];

while ($row = $r->fetch_assoc()) {
    $notificaciones[] = $row;
}

$fila->close();
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
                    <p><strong>Empresa:</strong> <?= htmlspecialchars($notificacion['empresa_emisora'] ?? 'Desconocida'); ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($notificacion['fecha_mensaje']); ?></p>
                    
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
                                        Mensaje de <?= htmlspecialchars($notificacion['empresa_emisora'] ?? 'Desconocida'); ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function marcarComoLeido(mensajeId) {
            // Enviar la solicitud para cambiar el estado a leído
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Si la solicitud fue exitosa, cambiar el estado en la interfaz
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









