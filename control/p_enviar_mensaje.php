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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_postulante = $_POST['id_postulante'];  
    $id_empresa = $_POST['id_empresa'];  
    $mensaje = mysqli_real_escape_string($cn, $_POST['mensaje']);  
    $fecha_envio = date('Y-m-d');  
    $id_emisor = $id_empresa;  
    $id_receptor = $id_postulante;  
    $id_tipo_mensaje = 2;  

    
    $sql = "INSERT INTO mensaje (mensaje, fecha_mensaje, id_estado_mensaje, id_usuario_emisor_mensaje, id_usuario_receptor_mensaje, id_tipo_mensaje) 
            VALUES (?, ?, 1, ?, ?, ?)";
    $stmt = mysqli_prepare($cn, $sql);
    mysqli_stmt_bind_param($stmt, "ssiii", $mensaje, $fecha_envio, $id_emisor, $id_receptor, $id_tipo_mensaje);

    if (mysqli_stmt_execute($stmt)) {
        
        echo "<script>
            alert('Mensaje enviado con éxito.');
            window.location.href = '../index.php?page=reporte_propuesta'; // Redirige a la página de propuestas
        </script>";
        exit();
    } else {
        echo "Error al enviar el mensaje: " . mysqli_error($cn);
    }
}
?>
