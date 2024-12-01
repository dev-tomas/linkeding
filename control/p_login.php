<?php
include("../sections/conexion.php");

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