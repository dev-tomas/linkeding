<?php
session_start(); 

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); 
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

include("../sections/conexion.php");

// Intentar obtener el ID de la propuesta de la sesión o de la URL
$id_propuesta = isset($_SESSION['propuesta_actual']) ? $_SESSION['propuesta_actual'] : 
                (isset($_GET['id']) ? intval($_GET['id']) : 
                (isset($_POST['id_propuesta']) ? intval($_POST['id_propuesta']) : null));

if ($id_propuesta === null) {
    die("No se pudo identificar la propuesta.");
}

// Fetch the empresa ID for the current user
$sql_empresa = "SELECT id_empresa FROM empresa WHERE id_usuario = ?";
$stmt_empresa = mysqli_prepare($cn, $sql_empresa);
mysqli_stmt_bind_param($stmt_empresa, "i", $usuario_id);
mysqli_stmt_execute($stmt_empresa);
$result_empresa = mysqli_stmt_get_result($stmt_empresa);

if (!$result_empresa || mysqli_num_rows($result_empresa) == 0) {
    die("Error al obtener el ID de la empresa o el usuario no tiene una empresa asociada.");
}

$empresa_data = mysqli_fetch_assoc($result_empresa);
$id_empresa = $empresa_data['id_empresa']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $id_postulante = $_POST['id_postulante'];
    $mensaje_texto = mysqli_real_escape_string($cn, $_POST['mensaje']);
    $fecha_envio = date('Y-m-d');

    // First, find the user ID of the postulante
    $sql_postulante_usuario = "SELECT id_usuario FROM postulante WHERE id_postulante = ?";
    $stmt_postulante_usuario = mysqli_prepare($cn, $sql_postulante_usuario);
    mysqli_stmt_bind_param($stmt_postulante_usuario, "i", $id_postulante);
    mysqli_stmt_execute($stmt_postulante_usuario);
    $result_postulante_usuario = mysqli_stmt_get_result($stmt_postulante_usuario);
    
    if (!$result_postulante_usuario || mysqli_num_rows($result_postulante_usuario) == 0) {
        die("Error al obtener el usuario del postulante.");
    }
    
    $postulante_usuario_data = mysqli_fetch_assoc($result_postulante_usuario);
    $id_usuario_receptor = $postulante_usuario_data['id_usuario'];

    // Find the current user's empresa
    $sql_usuario_empresa = "SELECT id_usuario FROM empresa WHERE id_empresa = ?";
    $stmt_usuario_empresa = mysqli_prepare($cn, $sql_usuario_empresa);
    mysqli_stmt_bind_param($stmt_usuario_empresa, "i", $id_empresa);
    mysqli_stmt_execute($stmt_usuario_empresa);
    $result_usuario_empresa = mysqli_stmt_get_result($stmt_usuario_empresa);
    
    if (!$result_usuario_empresa || mysqli_num_rows($result_usuario_empresa) == 0) {
        die("Error al obtener el usuario de la empresa.");
    }
    
    $empresa_usuario_data = mysqli_fetch_assoc($result_usuario_empresa);
    $id_usuario_emisor = $empresa_usuario_data['id_usuario'];

    // Start transaction
    mysqli_begin_transaction($cn);

    try {
        // Insert into mensaje table
        $sql_mensaje = "INSERT INTO mensaje (mensaje, fecha_mensaje, id_estado_mensaje, id_usuario_emisor_mensaje, id_usuario_receptor_mensaje) 
                        VALUES (?, ?, 1, ?, ?)";
        $stmt_mensaje = mysqli_prepare($cn, $sql_mensaje);
        mysqli_stmt_bind_param($stmt_mensaje, "ssii", $mensaje_texto, $fecha_envio, $id_usuario_emisor, $id_usuario_receptor);
        mysqli_stmt_execute($stmt_mensaje);
        
        // Get the last inserted mensaje id
        $id_mensaje = mysqli_insert_id($cn);

        // Insert into notificacion table
        $sql_notificacion = "INSERT INTO notificacion (id_propuesta, id_mensaje) VALUES (?, ?)";
        $stmt_notificacion = mysqli_prepare($cn, $sql_notificacion);
        mysqli_stmt_bind_param($stmt_notificacion, "ii", $id_propuesta, $id_mensaje);
        mysqli_stmt_execute($stmt_notificacion);

        // Commit transaction
        mysqli_commit($cn);

        echo "<script>
            alert('Mensaje enviado con éxito.');
            window.location.href = '../index.php?page=reporte_propuesta';
        </script>";
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($cn);
        echo "Error al enviar el mensaje: " . $e->getMessage();
        exit();
    }
}
?>