<?php
require_once 'control/p_header.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
</head>

<body>
    <div id="cssmenu">
        <div class="logo">
            <span>LINKEDING</span>
        </div>
        <nav>
            <ul>
                <?php if ($id_rol == 1): ?>
                    <li><a href="index.php?page=home">Principal</a></li>
                    <li class="has-sub">
                        <a href="#">Actualización</a>
                        <ul>
                            <li><a href="index.php?page=editar_datos_personales_usuario">Datos personales</a></li>
                            <li><a href="index.php?page=editar_foto_usuario">Cambiar foto de perfil</a></li>
                            <li><a href="index.php?page=editar_contrasena_usuario">Cambiar contraseña</a></li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="#">Reportes</a>
                        <ul>
                            <li><a href="index.php?page=reportes_admin">Postulantes</a></li>
                            <li><a href="index.php?page=reportes_empresa_admin">Empresas</a></li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="#">Atención</a>
                        <ul>
                            <li><a href="index.php?page=atencion_postulante">Postulantes</a></li>
                            <li><a href="index.php?page=atencion_empresa">Empresas</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php?page=estadistica">Estadística</a></li>
                    <li><a href="index.php?page=cerrar_sesion">Cerrar sesión</a></li>
                <?php elseif ($id_rol == 2): ?>
                    <li><a href="index.php?page=home">Principal</a></li>
                    <li class="has-sub">
                        <a href="#">Actualización</a>
                        <ul>
                            <li><a href="index.php?page=editar_datos_personales_usuario">Datos personales</a></li>
                            <li><a href="index.php?page=editar_foto_usuario">Cambiar foto de perfil</a></li>
                            <li><a href="index.php?page=editar_contrasena_usuario">Cambiar contraseña</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php?page=reporte_propuesta">Mis propuestas</a></li>
                    <li><a href="index.php?page=notificaciones">Notificaciones</a></li>
                    <li><a href="index.php?page=cerrar_sesion">Cerrar sesión</a></li>
                <?php elseif ($id_rol == 3): ?>
                    <li><a href="index.php?page=home">Principal</a></li>
                    <li class="has-sub">
                        <a href="#">Actualización</a>
                        <ul>
                            <li><a href="index.php?page=editar_datos_personales_usuario">Datos personales</a></li>
                            <li><a href="index.php?page=editar_foto_usuario">Cambiar foto de perfil</a></li>
                            <li><a href="index.php?page=editar_contrasena_usuario">Cambiar contraseña</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php?page=curriculum">Curriculum</a></li>
                    <li><a href="index.php?page=ofertas_laborales">Ofertas laborales</a></li>
                    <li><a href="index.php?page=mis_postulaciones">Postulaciones</a></li>
                    <li><a href="index.php?page=notificaciones">Notificaciones</a></li>
                    <li><a href="index.php?page=cerrar_sesion">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=login">Iniciar Sesión</a></li>
                    <li><a href="index.php?page=registro">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php if ($id_rol != 0): ?>
            <div class="profile-container">
                <a href="index.php?page=home"><?php echo htmlspecialchars ($nombre_usuario); ?></a>
                <a href="index.php?page=home">
                    <img src="<?php echo htmlspecialchars($ruta_imagen_usuario)?>?<?php echo time();?>" alt="home">
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
<?php
// Finaliza el buffer de salida
ob_end_flush();
?>