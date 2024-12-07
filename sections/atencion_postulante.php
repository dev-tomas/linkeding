<?php
include("conexion.php");

$sql = "SELECT p.id_postulante, p.id_estado_postulante, p.cip_postulante, p.dni_postulante, p.nombre_postulante, p.apellido_paterno_postulante, p.apellido_materno_postulante, 
               p.celular_postulante, p.direccion_postulante, p.fecha_nacimiento_postulante, es.nombre_estado_postulante,m.id_mensaje,m.mensaje,tm.nombre_tipo_mensaje,eq.id_estado_mensaje,eq.nombre_estado_mensaje
        FROM postulante p
        JOIN estado_postulante es ON p.id_estado_postulante = es.id_estado_postulante
        JOIN mensaje m on m.id_usuario_receptor_mensaje = p.id_usuario
        JOIN tipo_mensaje tm on tm.id_tipo_mensaje = m.id_tipo_mensaje
        JOIN estado_queja eq on eq.id_estado_mensaje = m.id_estado_mensaje"; 
$resultado = mysqli_query($cn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atención a Postulantes Suspendidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Postulantes Suspendidos</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>CIP</th>
            <th>DNI</th>
            <th>Nombre Completo</th>
            <th>Celular</th>
            <th>Dirección</th>
            <th>Fecha de Nacimiento</th>
            <th>Estado</th>
            <th>Tipo</th>
            <th>Comentario</th>
            <th>Check</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($postulante = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo $postulante['cip_postulante']; ?></td>
                <td><?php echo $postulante['dni_postulante']; ?></td>
                <td><?php echo $postulante['nombre_postulante'] . " " . $postulante['apellido_paterno_postulante'] . " " . $postulante['apellido_materno_postulante']; ?></td>
                <td><?php echo $postulante['celular_postulante']; ?></td>
                <td><?php echo $postulante['direccion_postulante']; ?></td>
                <td><?php echo $postulante['fecha_nacimiento_postulante']; ?></td>
                <td><?php echo $postulante['nombre_estado_postulante']; ?></td>
                <td><?php echo $postulante['nombre_tipo_mensaje']; ?></td>
                <td><?php echo $postulante['mensaje']; ?></td>
                <td>
                            <?php if ($postulante['nombre_estado_mensaje'] === 'enviado') { ?>
                                <form action="sections/p_leer_comen_pos.php" method="POST">
                                    <input type="hidden" name="id_comentario" value="<?php echo $postulante['id_mensaje']; ?>">
                                    <button type="submit" class="btn-leer">Marcar como leído</button>
                                </form>
                            <?php } else { ?>
                                <span class="text-muted">No disponible</span>
                            <?php } ?>
                </td>
                <td>
                    <?php if ($postulante['nombre_estado_postulante'] == 'Inactivo') { ?>
                        <button class="btn btn-success btn-sm cambiar-estado" data-id="<?php echo $postulante['id_postulante']; ?>" data-estado="Activo">Activar</button>
                    <?php } else { ?>
                        <button class="btn btn-warning btn-sm cambiar-estado" data-id="<?php echo $postulante['id_postulante']; ?>" data-estado="Inactivo">Cambiar a Inactivo</button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
$(document).on('click', '.cambiar-estado', function() {
    let id_postulante = $(this).data('id');
    let nuevoEstado = $(this).data('estado');

    if (confirm(`¿Estás seguro de que deseas cambiar el estado a '${nuevoEstado}'?`)) {
        $.ajax({
            url: 'sections/cambiar_estado_postulante.php',
            type: 'POST',
            data: { id_postulante: id_postulante, nuevo_estado: nuevoEstado },
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
