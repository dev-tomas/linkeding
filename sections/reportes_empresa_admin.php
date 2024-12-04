<?php
include("conexion.php");

$sql = "SELECT * FROM empresa";
$resultado = mysqli_query($cn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empresas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Listado de Empresas</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>RUC</th>
            <th>Razón Social</th>
            <th>Celular</th>
            <th>Dirección</th>
            <th>Representante</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($empresa = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo $empresa['id_empresa']; ?></td>
                <td><?php echo $empresa['ruc_empresa']; ?></td>
                <td><?php echo $empresa['razon_social_empresa']; ?></td>
                <td><?php echo $empresa['celular_empresa']; ?></td>
                <td><?php echo $empresa['direccion_empresa']; ?></td>
                <td><?php echo $empresa['representante_empresa']; ?></td>
                <td>
                    <button class="btn btn-primary btn-sm editar-empresa" data-id="<?php echo $empresa['id_empresa']; ?>" data-bs-toggle="modal" data-bs-target="#editarModal">Editar</button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal para Editar Empresa -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formActualizarEmpresa">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_empresa" name="id_empresa">
                    <div class="mb-3">
                        <label for="ruc_empresa" class="form-label">RUC:</label>
                        <input type="text" class="form-control" id="ruc_empresa" name="ruc_empresa" maxlength="11" required>
                    </div>
                    <div class="mb-3">
                        <label for="razon_social_empresa" class="form-label">Razón Social:</label>
                        <input type="text" class="form-control" id="razon_social_empresa" name="razon_social_empresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="celular_empresa" class="form-label">Celular:</label>
                        <input type="text" class="form-control" id="celular_empresa" name="celular_empresa" maxlength="12" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion_empresa" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion_empresa" name="direccion_empresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="representante_empresa" class="form-label">Representante:</label>
                        <input type="text" class="form-control" id="representante_empresa" name="representante_empresa" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).on('click', '.editar-empresa', function() {
    let idEmpresa = $(this).data('id');

    $.ajax({
        url: 'sections/obtener_empresa.php',
        type: 'POST',
        data: { id_empresa: idEmpresa },
        success: function(response) {
            let empresa = JSON.parse(response);
            $('#id_empresa').val(empresa.id_empresa);
            $('#ruc_empresa').val(empresa.ruc_empresa);
            $('#razon_social_empresa').val(empresa.razon_social_empresa);
            $('#celular_empresa').val(empresa.celular_empresa);
            $('#direccion_empresa').val(empresa.direccion_empresa);
            $('#representante_empresa').val(empresa.representante_empresa);
        }
    });
});

$('#formActualizarEmpresa').submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: 'sections/actualizar_admin_empresa.php',
        type: 'POST',
        data: $(this).serialize(),
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
});
</script>
</body>
</html>