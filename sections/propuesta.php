<?php
include("conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Propuesta</title>
    <link href="../css/propuesta.css" rel="stylesheet">
</head>
<body>

<h2>Registrar Propuesta</h2>

<?php
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        echo '<p style="color: green;">Propuesta registrada exitosamente.</p>';
    } elseif ($_GET['status'] === 'error') {
        echo '<p style="color: red;">Ocurrió un error al registrar la propuesta. Intente nuevamente.</p>';
    }
}
?>

<form action="../control/p_propuesta.php" method="POST">
    <label for="propuesta">Nombre de la Propuesta:</label>
    <input type="text" id="propuesta" name="propuesta" required><br><br>
    
    <label for="descripcion">Descripción de la Propuesta:</label>
    <textarea id="descripcion" name="descripcion" rows="4" required></textarea><br><br>
    
    <label for="requisitos">Requisitos:</label>
    <textarea id="requisitos" name="requisitos" rows="4" required></textarea><br><br>
    
    <label for="fechalimite">Fecha Límite:</label>
    <input type="date" id="fechalimite" name="fechalimite" required><br><br>
    
    <input type="submit" value="Registrar Propuesta">
</form>

</body>
</html>
