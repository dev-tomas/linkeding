<?php
include("conexion.php");

// Updated SQL query to match the new database structure
$sql = "SELECT e.id_empresa, e.ruc_empresa, e.razon_social_empresa, e.celular_empresa, 
               e.direccion_empresa, e.representante_empresa, 
               es.nombre_estado_empresa, 
               m.id_mensaje, m.mensaje, 
               em.nombre_estado_mensaje
        FROM empresa e 
        JOIN estado_empresa es ON e.id_estado_empresa = es.id_estado_empresa
        JOIN mensaje m ON m.id_usuario_receptor_mensaje = e.id_usuario
        JOIN estado_mensaje em ON m.id_estado_mensaje = em.id_estado_mensaje
        WHERE m.id_mensaje IN (
            SELECT id_mensaje FROM queja
        )"; 
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
            <th>RUC</th>
            <th>Razón Social</th>
            <th>Celular</th>
            <th>Dirección</th>
            <th>Representante</th>
            <th>Estado Empresa</th>
            <th>Mensaje</th>
            <th>Estado Mensaje</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($empresa = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($empresa['ruc_empresa']); ?></td>
                <td><?php echo htmlspecialchars($empresa['razon_social_empresa']); ?></td>
                <td><?php echo htmlspecialchars($empresa['celular_empresa']); ?></td>
                <td><?php echo htmlspecialchars($empresa['direccion_empresa']); ?></td>
                <td><?php echo htmlspecialchars($empresa['representante_empresa']); ?></td>
                <td><?php echo htmlspecialchars($empresa['nombre_estado_empresa']); ?></td>
                <td><?php echo htmlspecialchars($empresa['mensaje']); ?></td>
                <td><?php echo htmlspecialchars($empresa['nombre_estado_mensaje']); ?></td>
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