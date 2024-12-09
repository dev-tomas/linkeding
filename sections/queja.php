<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verificar inicio de sesión y permisos
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al login si no hay sesión
    header('Location: login.php');
    exit();
}

include __DIR__.'/conexion.php';
// Verificar conexión
if (!$cn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Variables para manejar diferentes tipos de quejas
$id_postulante = isset($_GET['id_postulante']) ? intval($_GET['id_postulante']) : null;
$id_empresa = isset($_GET['id_empresa']) ? intval($_GET['id_empresa']) : null;

// ID del usuario administrador (según mencionaste que es el ID 3)
$id_admin = 3;

// Procesar el envío de la queja
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que se haya enviado un mensaje
    if (empty(trim($_POST['mensaje']))) {
        $error = "El mensaje de la queja no puede estar vacío.";
    } else {
        try {
            // Iniciar transacción para asegurar integridad de datos
            mysqli_begin_transaction($cn);

            // 1. Insertar en la tabla mensaje
            $mensaje = mysqli_real_escape_string($cn, $_POST['mensaje']);
            $query_mensaje = "INSERT INTO mensaje (
                mensaje, 
                fecha_mensaje, 
                id_estado_mensaje, 
                id_usuario_emisor_mensaje, 
                id_usuario_receptor_mensaje
            ) VALUES (
                '$mensaje', 
                CURDATE(), 
                1, 
                '{$_SESSION['usuario_id']}', 
                '$id_admin'
            )";
            
            if (!mysqli_query($cn, $query_mensaje)) {
                throw new Exception("Error al insertar mensaje: " . mysqli_error($cn));
            }
            $id_mensaje = mysqli_insert_id($cn);

            // 2. Insertar en la tabla queja
            // Determinar el ID del usuario quejado (postulante o empresa)
            if ($id_postulante) {
                $query_usuario = "SELECT id_usuario FROM postulante WHERE id_postulante = '$id_postulante'";
            } else {
                $query_usuario = "SELECT id_usuario FROM empresa WHERE id_empresa = '$id_empresa'";
            }
            
            $result_usuario = mysqli_query($cn, $query_usuario);
            $row_usuario = mysqli_fetch_assoc($result_usuario);
            $usuario_quejado = $row_usuario['id_usuario'];

            $query_queja = "INSERT INTO queja (
                id_usuario_queja, 
                id_mensaje
            ) VALUES (
                '$usuario_quejado', 
                '$id_mensaje'
            )";
            
            if (!mysqli_query($cn, $query_queja)) {
                throw new Exception("Error al insertar queja: " . mysqli_error($cn));
            }

            // Confirmar transacción
            mysqli_commit($cn);

            // Redirigir con mensaje de éxito
            header("Location: ../index.php?page=queja&mensaje=Queja registrada exitosamente");
            exit();

        } catch (Exception $e) {
            // Revertir transacción en caso de error
            mysqli_rollback($cn);
            $error = "Error al registrar la queja: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Queja</title>
    <link rel="stylesheet" href="../css/queja.css">
</head>
<body>
    <div class="complaint-container">
        <div class="form-wrapper">
            <h2>Registrar Queja</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="mensaje">Descripción de la Queja</label>
                    <textarea 
                        name="mensaje" 
                        id="mensaje" 
                        class="form-control" 
                        rows="4" 
                        required
                        placeholder="Describe detalladamente el motivo de tu queja"
                    ><?php echo isset($_POST['mensaje']) ? htmlspecialchars($_POST['mensaje']) : ''; ?></textarea>
                </div>

                <?php if ($id_postulante): ?>
                    <input type="hidden" name="id_postulante" value="<?php echo $id_postulante; ?>">
                <?php endif; ?>

                <?php if ($id_empresa): ?>
                    <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">Enviar Queja</button>
            </form>
        </div>
    </div>
</body>
</html>