<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="main-container">
        <div class="title-section">
            <table>
                <tr>
                    <td style="text-align: center;">
                        <img src="../img/logo_cip.png" alt="Logo">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h1>Bienvenido a Linkeding</h1>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="form-section">
            <form action="../control/p_login.php" method="POST">
                <center><h2>Ingrese su cuenta</h2></center>
                <br>
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" placeholder="Ingresa tu usuario" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>
                <button type="submit"><strong>Ingresar</strong></button>
                <div class="link">
                    <a href="registro.php">No tienes una cuenta? Regístrate</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>