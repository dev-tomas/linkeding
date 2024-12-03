<?php
include("conexion.php");

// Check if user is logged in
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$cod = $_SESSION['usuario_id'];

$sql = "SELECT p.*, u.*
FROM usuario u
INNER JOIN empresa p ON u.id_usuario = p.id_usuario
WHERE p.id_usuario = $cod";

$result = mysqli_query($cn, $sql);

// Check if query was successful
if (!$result) {
    die("Error in query: " . mysqli_error($cn));
}

$usuario = mysqli_fetch_assoc($result);

// Check if user was found
if (!$usuario) {
    die("No user found");
}

$razon_social = $usuario['razon_social_empresa'];
$ruta_imagen_usuario = $usuario['ruta_imagen_usuario'] ?? 'default.png';
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
    <div class="form-container">

        <!-- Encabezado con la foto del usuario -->
        <h2>REGISTRAR PROPUESTA</h2>

        <div class="comment-header">
            <img src="../img/usuario/<?php echo $ruta_imagen_usuario; ?>?<?php echo time(); ?>"
                alt="Foto de perfil" class="profile-image">
            <div class="user-info">
                <p class="user-name"><?php echo $razon_social; ?></p>
                <span class="timestamp"><?php echo date('d M Y, H:i'); ?></span>
            </div>
        </div>

        
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'success') {
                echo '<p class="success-message">Propuesta registrada exitosamente.</p>';
            } elseif ($_GET['status'] === 'error') {
                echo '<p class="error-message">Ocurrió un error al registrar la propuesta. Intente nuevamente.</p>';
            }
        }
        ?>
        <form action="../control/p_propuesta.php" method="POST">
            <!-- Columna izquierda -->
            <div class="form-group">
                <label for="propuesta">Nombre de la Propuesta:</label>
                <input type="text" id="propuesta" name="propuesta" required>
            </div>
            <div class="form-group">
                <label for="fechalimite">Fecha Límite:</label>
                <input type="date" id="fechalimite" name="fechalimite" required>
            </div>

            <!-- Columna derecha -->
            <div class="form-group">
                <label for="descripcion">Descripción de la Propuesta:</label>
                <textarea id="descripcion" name="descripcion" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label for="requisitos">Requisitos:</label>
                <textarea id="requisitos" name="requisitos" rows="4" required></textarea>
            </div>

            <!-- Botón centrado en ambas columnas -->
            <div class="form-group full-width">
                <button type="submit">Registrar Propuesta</button>
            </div>
        </form>
    </div>
</body>

</html>