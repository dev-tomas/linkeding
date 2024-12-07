<?php 
session_start();   
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit(); 
}

$usuario_id = $_SESSION['usuario_id'];   
include("../sections/conexion.php");   

$sql_empresa = "SELECT id_empresa FROM empresa WHERE id_usuario = $usuario_id"; 
$result_empresa = mysqli_query($cn, $sql_empresa);  

if (!$result_empresa || mysqli_num_rows($result_empresa) == 0) {
    die("Error al obtener el ID de la empresa o el usuario no tiene una empresa asociada."); 
}  

$empresa_data = mysqli_fetch_assoc($result_empresa); 
$id_empresa = $empresa_data['id_empresa'];   

if (isset($_GET['id_postulante'])) {
    $id_postulante = $_GET['id_postulante'];
    
    // Obtener el ID de la propuesta directamente de GET
    $id_propuesta = isset($_GET['id_propuesta']) ? intval($_GET['id_propuesta']) : null;
    
    if ($id_propuesta === null) {
        die("ID de la propuesta no encontrado.");
    }
} else {
    echo "ID del postulante no encontrado.";
    exit(); 
} 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Mensaje</title>
    <link rel="stylesheet" href="../css/enviar_mensaje.css">
</head>
<body>
    <div class="container">
        <h2>Enviar Mensaje al Postulante</h2>
        <form action="../control/p_enviar_mensaje.php" method="POST">
            <label for="mensaje">Escribe tu mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
            
            <input type="hidden" name="id_postulante" value="<?php echo $id_postulante; ?>">
            <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">
            <input type="hidden" name="id_propuesta" value="<?php echo $id_propuesta; ?>">
            
            <button type="submit">Enviar</button>
        </form>
        <a href="reporte_propuesta">Volver a las propuestas</a>
    </div>
</body>
</html>