<?php
include("conexion.php");

$id = $_GET["id"];

$sql = "SELECT * FROM propuesta WHERE id_propuesta = $id";
$fila = mysqli_query($cn, $sql);
$r = mysqli_fetch_assoc($fila);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Propuesta</title>
    <link href="../css/propuesta.css" rel="stylesheet"> 
</head>
<body>

    <!-- Contenido principal del formulario de edición -->
    <div class="content">
        <div class="container">
            <form action="http://localhost/linkeding/control/p_editar_propuesta.php" method="post">
                <fieldset>
                    <legend>Editar Propuesta</legend>
                    <div class="form-group">
                        <label for="txtpropuesta">PROPUESTA</label>
                        <input type="text" id="txtpropuesta" name="txtpropuesta" required value="<?php echo $r['nombre_propuesta']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">DESCRIPCIÓN</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required><?php echo $r['descripcion_propuesta']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="requisitos">REQUISITOS</label>
                        <textarea id="requisitos" name="requisitos" rows="4" required><?php echo $r['requisitos_propuesta']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fechalimite">FECHA LIMITE</label>
                        <input type="date" id="fechalimite" name="fechalimite" required value="<?php echo $r['fecha_limite']; ?>">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" value="Editar Propuesta">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

</body>
</html>
