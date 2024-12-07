<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/registro.css">
    <title>Registro de Usuario</title>
</head>
<body>
    <div class="container">
        <form action="../control/p_registro.php" method="POST" enctype="multipart/form-data">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <div class="form-left">
                            <h2>REGISTRO</h2>
                            <br>
                            <br>
                            <label for="username">Nombre de Usuario:</label>
                            <input type="text" id="username" name="username" required>

                            <label for="password">Contraseña:</label>
                            <input type="password" id="password" name="password" required>

                            <label for="rol">Rol:</label>
                            <select id="rol" name="rol" required>
                                <option value="">Seleccione un rol</option>
                                <option value="2">Empresa</option>
                                <option value="3">Postulante</option>
                            </select>
                            <label for="foto">Foto (opcional):</label>
                            <input type="file" id="foto" name="foto" accept="image/*">
                            <label for="foto">Foto Portada:</label>
                            <input type="file" id="fotoportada" name="fotoportada" accept="image/*">
                            <div class="form-bottom">
                                <button type="submit">Registrar</button>
                                <button type="button" class="regresar" onclick="window.history.back()">Regresar</button>
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <div class="form-right">
                            <div id="dynamic-fields" class="dynamic-fields">
                                <!-- Campos dinámicos aquí -->
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script src="../js/registro.js"></script>
</body>
</html>