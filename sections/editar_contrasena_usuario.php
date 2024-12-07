<?php

include("conexion.php");


$idUsuario = $_SESSION["usuario_id"]; // Asegúrate de que auth.php establece esto correctamente

// Mostrar mensaje de error si existe
if (isset($_GET['error'])) {
    $mensajeError = "";
    switch($_GET['error']){
        case 'contrasenas_no_coinciden': $mensajeError = "Las contraseñas no coinciden."; break;
        case 'contrasena_corta': $mensajeError = "La contraseña debe tener al menos 8 caracteres."; break;
        case 'actualizacion_fallida': $mensajeError = "Error al actualizar la contraseña. Intenta de nuevo."; break;
    }
    echo "<div style='color: red; text-align: center;'>$mensajeError</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
</head>
<body>
    <br>
    <center>
    <h2>Cambiar Contraseña</h2>
    <br>
<form action="../control/p_actualizar_contrasena.php" method="post">

    <table border="1" cellspacing="0" align="center" bgcolor="lightblue" width="600">
        <tr>
            <td>Nueva contraseña (mínimo 8 caracteres)</td>
            <td> <input type="password" name="txtpass" required></td>
        </tr>
        <tr>
            <td>Repetir contraseña</td>
            <td><input type="password" name="txtrepass" required></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="Cambiar contraseña">
            </td>
        </tr>
    </table>

</form>
    </center>
</body>
</html>