<?php
session_start();
include("../sections/conexion.php");
// Verificar si se envió el formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_usuario_logueado = $_SESSION['usuario_id'];
    $id_propuesta = $_POST['id_propuesta'];
    $fecha_postulacion = date('Y-m-d');


    $sql_postulante = "SELECT id_postulante FROM postulante WHERE id_usuario = ?";
    $stmt_postulante = mysqli_prepare($cn, $sql_postulante);
    mysqli_stmt_bind_param($stmt_postulante, "i", $id_usuario_logueado);
    mysqli_stmt_execute($stmt_postulante);
    $resultado_postulante = mysqli_stmt_get_result($stmt_postulante);
    $postulante = mysqli_fetch_assoc($resultado_postulante);
    $id_postulante = $postulante['id_postulante'];


    // Verificar si ya existe una postulación
    $sqli = "SELECT * FROM detalle_postulante_propuesta WHERE id_postulante = ? AND id_propuesta = ?";
    $filas = $cn->prepare($sqli);
    $filas->bind_param("ii", $id_postulante, $id_propuesta);
    $filas->execute();
    $resultado = $filas->get_result();

    if ($resultado->num_rows > 0) {
        echo "<script>
            alert('Ya te has postulado a esta propuesta.');
            window.location.href = '../index.php?page=ofertas_laborales';
        </script>";
        exit;
    }


    // Si no existe la postulación, se procede a registrar
    $sql = "INSERT INTO detalle_postulante_propuesta (id_postulante, id_propuesta, fecha_postulacion) 
            VALUES (?, ?, ?)";
    
    if ($fila = $cn->prepare($sql)) {
        $fila->bind_param("iis", $id_postulante, $id_propuesta, $fecha_postulacion);

        if ($fila->execute()) {
            // Redirigir a una página de confirmación o al listado de postulaciones
            header("Location: ../index.php?page=mis_postulaciones");
            exit;
        } else {
            echo "Error al registrar la postulación: " . $filat->error;
        }

        $fila->close();
    } else {
        echo "Error al preparar la consulta: " . $cn->error;
    }
} else {
    echo "Acceso no permitido.";
}
?>
