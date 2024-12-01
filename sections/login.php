<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql_usuario = "SELECT * FROM usuario WHERE nombre_usuario = ?";
    $stmt = mysqli_prepare($cn, $sql_usuario);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result_usuario = mysqli_stmt_get_result($stmt);

    if ($result_usuario && mysqli_num_rows($result_usuario) > 0) {
        $r_usuario = mysqli_fetch_assoc($result_usuario);

        // Comparación directa de contraseñas
        if ($password == $r_usuario['contrasena_usuario']) {
            // Iniciar sesión correctamente
            session_start();
            $_SESSION['usuario_id'] = $r_usuario['id_usuario'];
            $_SESSION['nombre_usuario'] = $r_usuario['nombre_usuario'];
            $_SESSION['id_rol'] = $r_usuario['id_rol']; // Almacenar el rol del usuario

            header("Location: ../index.php");
            exit();
        } else {
            $error_message = "Contraseña Invalida!";
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
    <title>Login - MyLinkedIn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">Welcome to MyLinkedIn</h3>
            <?php if (isset($error_message)): ?>
                <p class="text-center error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Log In</button>
            </form>
            <div class="text-center mt-3">
                <a href="registro.php" class="text-decoration-none">No tienes una cuenta? Registrate</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
