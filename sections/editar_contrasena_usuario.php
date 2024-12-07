<?php
// Incluir la conexión a la base de datos y cualquier validación previa, como la autenticación.
include("conexion.php"); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="css/cambiar_password.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Cambiar Contraseña</h2>
            
            <!-- Formulario para cambiar la contraseña -->
            <form action="http://localhost/linkeding/control/p_actualizar_contrasena.php" method="post">
                <div class="input-group">
                    <label for="txtpass">Nueva contraseña (mínimo 8 caracteres)</label>
                    <input type="password" id="txtpass" name="txtpass" required>
                </div>

                <div class="input-group">
                    <label for="txtrepass">Repetir contraseña</label>
                    <input type="password" id="txtrepass" name="txtrepass" required>
                </div>

                <div class="input-group">
                    <input type="submit" value="Cambiar contraseña">
                </div>
            </form>

            <!-- Manejo de errores, si los hay -->
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'contrasenas_no_coinciden') {
                    echo "<p class='error'>Las contraseñas no coinciden. Por favor, inténtelo nuevamente.</p>";
                } elseif ($_GET['error'] == 'contrasena_corta') {
                    echo "<p class='error'>La contraseña debe tener al menos 8 caracteres.</p>";
                } elseif ($_GET['error'] == 'actualizacion_fallida') {
                    echo "<p class='error'>Hubo un error al actualizar la contraseña. Inténtelo más tarde.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
