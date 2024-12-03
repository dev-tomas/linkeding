<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    die("Error: No se encontró una sesión activa. Por favor, inicia sesión.");
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../sections/conexion.php");
$id_usuario_logueado = $_SESSION['usuario_id'];
// Validar que todos los campos estén presentes
$sql_empresa = "SELECT id_empresa FROM empresa WHERE id_usuario = ?";
$stmt_empresa = mysqli_prepare($cn, $sql_empresa);
mysqli_stmt_bind_param($stmt_empresa, "i", $id_usuario_logueado);
mysqli_stmt_execute($stmt_empresa);
$resultado_empresa = mysqli_stmt_get_result($stmt_empresa);
$empresa = mysqli_fetch_assoc($resultado_empresa);
    $id_empresa = $empresa['id_empresa'];
// Validar que el usuario tenga una sesión activa con id_usuario

$propuesta = mysqli_real_escape_string($cn, $_POST['propuesta']);
$descripcion = mysqli_real_escape_string($cn, $_POST['descripcion']);
$requisitos = mysqli_real_escape_string($cn, $_POST['requisitos']);
$fechalimite = mysqli_real_escape_string($cn, $_POST['fechalimite']);

$id_estado_propuesto = 1; // Suponiendo un estado inicial
//$_SESSION['id_usuario']; // ID de la empresa asociada
$fecha_hoy = date('Y-m-d'); // Fecha actual

// Iniciar una transacción
mysqli_begin_transaction($cn);

try {
    // Insertar la propuesta
    $sql_propuesta = "INSERT INTO propuesta (nombre_propuesta, descripcion_propuesta, requisitos_propuesta, fecha_limite, id_estado_propuesta) 
                      VALUES ('$propuesta', '$descripcion', '$requisitos', '$fechalimite', '$id_estado_propuesto')";
    if (!mysqli_query($cn, $sql_propuesta)) {
        throw new Exception("Error al registrar la propuesta: " . mysqli_error($cn));
    }

    // Obtener el ID de la propuesta recién insertada
    $id_propuesta = mysqli_insert_id($cn);

    // Insertar el registro en detalle_empresa_propuesta
    $sql_detalle = "INSERT INTO detalle_empresa_propuesta (id_propuesta, id_empresa, fecha_publicacion) 
                    VALUES ($id_propuesta, $id_empresa, '$fecha_hoy')";
    if (!mysqli_query($cn, $sql_detalle)) {
        throw new Exception("Error al registrar en detalle_empresa_propuesta: " . mysqli_error($cn));
    }

    // Confirmar la transacción
    mysqli_commit($cn);
    echo "<script>
                alert('Registro Exitoso');
                window.location.href = '../index.php?page=reporte_propuesta';
                </script>";
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    mysqli_rollback($cn);
    error_log($e->getMessage());
    echo "<script>
                alert('Ha fallado algo');
                window.location.href = '../index.php?page=reporte_propuesta';
                </script>";
}

exit;
?>