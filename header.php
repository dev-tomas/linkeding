<?php
require_once __DIR__.'/control/p_header.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <style>
        <?php if ($id_rol != 1): ?>
            #cssmenu {
            background-color: #6c0e10;
            color: white;
            padding: 10px 0;
        }

        #cssmenu .logo {
            font-size: 24px;
            color: white;
            font-weight: bold;
            padding-left: 20px;
        }

        #cssmenu nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-end;
        }

        #cssmenu nav ul li {
            position: relative;
        }

        #cssmenu nav ul li a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
        }

        #cssmenu nav ul li a:hover {
            background-color: black; /* Color de hover */
        }

        #cssmenu .has-sub > a:after {
            content: " ▼";
        }

        #cssmenu .has-sub:hover > ul {
            display: block;
        }

        #cssmenu nav ul li ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #6c0e10;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            padding: 0;
        }

        #cssmenu nav ul li ul li a {
            padding: 10px 20px;
            color: white;
        }

        #cssmenu .profile-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 20px;
        }

        #cssmenu .profile-container a {
            color: white;
            text-decoration: none;
            padding-right: 15px;
        }

        #cssmenu .profile-container img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        <?php endif; ?>
    </style>
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
                <a href="index.php?page=home"><?php echo htmlspecialchars ($nombre_titular); ?></a>
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