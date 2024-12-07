
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Fotos</title>
    <link rel="stylesheet" href="../css/editar_foto_usuario.css">
</head>
<body>
    <div class="container">
        <h2>Editar Fotos</h2>
        <form action="http://localhost/linkeding/control/p_editar_foto.php" method="POST" enctype="multipart/form-data">
            <label for="foto">Cambiar foto de perfil:</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            
            <label for="fotoportada">Cambiar foto de portada:</label>
            <input type="file" id="fotoportada" name="fotoportada" accept="image/*">
            
            <button type="submit">Actualizar Fotos</button>
        </form>
    </div>
</body>
</html>