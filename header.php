<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Obtener el ID del rol del usuario
$id_rol = $_SESSION['id_rol'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Dinámico</title>
    <style>
        header {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            border-bottom: 2px solid #ccc;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 10px;
        }
        nav ul li a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
<header>
    <h1>Bienvenido</h1>
    <nav>
        <ul>
            <?php if ($id_rol == 1): ?>
                <li><a href="dashboard_admin.php">Dashboard</a></li>
                <li><a href="usuarios.php">Gestionar Usuarios</a></li>
                <li><a href="reportes.php">Reportes</a></li>
            <?php elseif ($id_rol == 2): ?>
                <li><a href="publicar_oferta.php">Publicar Oferta</a></li>
                <li><a href="mis_ofertas.php">Mis Ofertas</a></li>
                <li><a href="perfil_empresa.php">Perfil</a></li>
            <?php elseif ($id_rol == 3): ?>
                <li><a href="buscar_empleos.php">Buscar Empleos</a></li>
                <li><a href="mis_postulaciones.php">Mis Postulaciones</a></li>
                <li><a href="perfil_postulante.php">Perfil</a></li>
            <?php else: ?>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="registro.php">Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
</body>
</html>
