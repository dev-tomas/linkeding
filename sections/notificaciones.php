<?php
include("conexion.php");


$postulante = $_SESSION['usuario_id']; 


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


// Marcar mensaje como leído si el usuario lo abre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mensaje_id'])) {
    $mensaje = intval($_POST['mensaje_id']);

    $sqli = "update mensaje set id_estado_mensaje = 2 where id_mensaje = ? and id_usuario_receptor_mensaje = ?";
    $filas = $cn->prepare($sqli);
    $filas->bind_param("ii", $mensaje, $postulante);
    $filas->execute();
    $filas->close();

    
    header("Location: index.php?page=notificaciones");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link rel="stylesheet" href="../css/notificaciones_postulante.css">
   
</head>
<body>
    <h1>Notificaciones</h1>
    <div>
        <?php if (empty($notificaciones)): ?>
            <p>No tienes notificaciones.</p>
        <?php else: ?>
            <?php foreach ($notificaciones as $notificacion): ?>
                <div class="notificacion <?= $notificacion['estado'] === 'leído' ? 'leido' : 'no-leido'; ?>">
                    <p><strong>Empresa:</strong> <?= htmlspecialchars($notificacion['empresa_emisora'] ?? 'Desconocida'); ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($notificacion['fecha_mensaje']); ?></p>
                    <p><strong>Mensaje:</strong> <?= htmlspecialchars($notificacion['mensaje']); ?></p>
                    <?php if ($notificacion['estado'] !== 'leído'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="mensaje_id" value="<?= $notificacion['id_mensaje']; ?>">
                            <button type="submit" class="boton-ver">Marcar como leído</button>
                        </form>
                    <?php else: ?>
                        <p><em>Ya leído</em></p>
                    <?php endif; ?>
                </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>