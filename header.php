<?php
// Coloca esta línea al principio para evitar problemas de salida
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener el ID del rol del usuario, con un valor predeterminado seguro
$id_rol = $_SESSION['id_rol'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Dinámico</title>
    <!-- Referencia al archivo CSS -->
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
<div class="header-container">
    <header>
        <h1>Bienvenido</h1>
        <nav>
            <ul>
                <?php if ($id_rol == 1): ?>
                    <li><a href="index.php?page=home">Principal</a></li>
                    <li><a href="index.php?page=usuarios">Actualización</a></li>
                    <li><a href="index.php?page=reportes_admin">Reportes</a></li>
                    <li><a href="index.php?page=atencion">Atención</a></li>
                    <li><a href="index.php?page=estadistica">Estadística</a></li>
                    <li><a href="index.php?page=cerrar_sesion">Cerrar sesión</a></li>
                <?php elseif ($id_rol == 2): ?>
                    <li><a href="index.php?page=home">Principal</a></li>
                    <li><a href="index.php?page=actualizacion">Actualización</a></li>
                    <li><a href="index.php?page=propuesta">Ingresar propuesta</a></li>
                    <li><a href="index.php?page=reporte_propuesta">Ver propuestas</a></li>
                    <li><a href="index.php?page=cerrar_sesion">Cerrar sesión</a></li>
                <?php elseif ($id_rol == 3): ?>
                    <li><a href="index.php?page=home">Principal</a></li>
                    <li><a href="index.php?page=actualizacion">Actualización</a></li>
                    <li><a href="index.php?page=curriculum">Curriculum</a></li>
                    <li><a href="index.php?page=ofertas_laborales">Ofertas laborales</a></li>
                    <li><a href="index.php?page=mis_postulaciones">Mis Postulaciones</a></li>
                    <li><a href="index.php?page=cerrar_sesion">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=login">Iniciar Sesión</a></li>
                    <li><a href="index.php?page=registro">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</div>
</body>
</html>
<?php
// Finaliza el buffer de salida
ob_end_flush();
?>