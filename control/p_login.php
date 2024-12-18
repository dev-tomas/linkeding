<?php
include("../sections/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Acá traje los id_estado_postulante
    $sql_usuario = "SELECT u.*, p.id_estado_postulante 
                    FROM usuario u
                    LEFT JOIN postulante p ON u.id_usuario = p.id_usuario
                    WHERE u.nombre_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result_usuario = mysqli_stmt_get_result($stmt);

    if ($result_usuario && mysqli_num_rows($result_usuario) > 0) {
        $r_usuario = mysqli_fetch_assoc($result_usuario);

        // Verificaciones con condiciones
        if (isset($r_usuario['id_estado_postulante'])) {
            if ($r_usuario['id_estado_postulante'] == 2) {
                $error_message = "Su cuenta está inactiva. Contacte al administrador.";
            } elseif ($r_usuario['id_estado_postulante'] == 3) {
                $error_message = "Su cuenta está suspendida. Contacte al administrador.";
            } elseif ($r_usuario['id_estado_postulante'] == 4) {
                $error_message = "Su cuenta ha sido eliminada.";
            }
        }

        // Si no hay errores de estado, verifica la contraseña
        if (empty($error_message) && $password == $r_usuario['contrasena_usuario']) {
            // Iniciar sesión correctamente
            session_start();
            $_SESSION['usuario_id'] = $r_usuario['id_usuario'];
            $_SESSION['nombre_usuario'] = $r_usuario['nombre_usuario'];
            $_SESSION['id_rol'] = $r_usuario['id_rol']; // Almacenar el rol del usuario

            header("Location: ../index.php");
            exit();
        } else {
            $error_message = $error_message ?? "Contraseña Inválida!";
        }
    } else {
        $error_message = "El usuario no existe!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h3>Error de Autenticación</h3>
            <p class="error-message"><?php echo $error_message; ?></p>
            <a href="../sections/login.php">Volver al Login</a>
        </div>
    </div>
</body>
</html>
