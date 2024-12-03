<?php
include("conexion.php");

$sql = "SELECT * FROM postulante";
$resultado = mysqli_query($cn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Postulantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Listado de Postulantes</h2>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>CIP</th>
            <th>DNI</th>
            <th>Nombre Completo</th>
            <th>Celular</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($postulante = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo $postulante['id_postulante']; ?></td>
                <td><?php echo $postulante['cip_postulante']; ?></td>
                <td><?php echo $postulante['dni_postulante']; ?></td>
                <td><?php echo $postulante['nombre_postulante'] . ' ' . $postulante['apellido_paterno_postulante'] . ' ' . $postulante['apellido_materno_postulante']; ?></td>
                <td><?php echo $postulante['celular_postulante']; ?></td>
                <td><?php echo $postulante['direccion_postulante']; ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editarModal"
                            data-id="<?php echo $postulante['id_postulante']; ?>"
                            data-cip="<?php echo $postulante['cip_postulante']; ?>"
                            data-dni="<?php echo $postulante['dni_postulante']; ?>"
                            data-nombre="<?php echo $postulante['nombre_postulante']; ?>"
                            data-apellido_paterno="<?php echo $postulante['apellido_paterno_postulante']; ?>"
                            data-apellido_materno="<?php echo $postulante['apellido_materno_postulante']; ?>"
                            data-celular="<?php echo $postulante['celular_postulante']; ?>"
                            data-direccion="<?php echo $postulante['direccion_postulante']; ?>"
                            data-fecha_nacimiento="<?php echo $postulante['fecha_nacimiento_postulante']; ?>">
                        Editar
                    </button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal para editar -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel">Editar Postulante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="sections/actualizar_postulante.php">
                <div class="modal-body">
                    <input type="hidden" name="id_postulante" id="modal-id">
                    <div class="mb-3">
                        <label for="modal-cip" class="form-label">CIP:</label>
                        <input type="text" class="form-control" id="modal-cip" name="cip_postulante" maxlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-dni" class="form-label">DNI:</label>
                        <input type="text" class="form-control" id="modal-dni" name="dni_postulante" maxlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="modal-nombre" name="nombre_postulante" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-apellido-paterno" class="form-label">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="modal-apellido-paterno" name="apellido_paterno_postulante" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-apellido-materno" class="form-label">Apellido Materno:</label>
                        <input type="text" class="form-control" id="modal-apellido-materno" name="apellido_materno_postulante" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-celular" class="form-label">Celular:</label>
                        <input type="text" class="form-control" id="modal-celular" name="celular_postulante" maxlength="12" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="modal-direccion" name="direccion_postulante" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-fecha-nacimiento" class="form-label">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control" id="modal-fecha-nacimiento" name="fecha_nacimiento_postulante" required>
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
    var editarModal = document.getElementById('editarModal');
    editarModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var cip = button.getAttribute('data-cip');
        var dni = button.getAttribute('data-dni');
        var nombre = button.getAttribute('data-nombre');
        var apellidoPaterno = button.getAttribute('data-apellido_paterno');
        var apellidoMaterno = button.getAttribute('data-apellido_materno');
        var celular = button.getAttribute('data-celular');
        var direccion = button.getAttribute('data-direccion');
        var fechaNacimiento = button.getAttribute('data-fecha_nacimiento');

        document.getElementById('modal-id').value = id;
        document.getElementById('modal-cip').value = cip;
        document.getElementById('modal-dni').value = dni;
        document.getElementById('modal-nombre').value = nombre;
        document.getElementById('modal-apellido-paterno').value = apellidoPaterno;
        document.getElementById('modal-apellido-materno').value = apellidoMaterno;
        document.getElementById('modal-celular').value = celular;
        document.getElementById('modal-direccion').value = direccion;
        document.getElementById('modal-fecha-nacimiento').value = fechaNacimiento;
    });
</script>
</body>
</html>
