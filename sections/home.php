<?php
require_once __DIR__ . '/../control/p_home.php';
require_once __DIR__ . '/../control/p_obtener_curriculum.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - LINKEDING</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <div class="linkeding-home">
        <!-- Sección de Portada -->
        <div class="profile-cover">
            <img src="<?php echo htmlspecialchars($ruta_imagen_portada) ?>?<?php echo time(); ?>" alt="Portada"
                class="cover-image">
        </div>

        <?php if ($id_rol == 3): ?>
            <!-- Contenedor principal del perfil -->
            <div class="profile-main">
                <!-- Foto de perfil y nombre -->
                <div class="profile-header">
                    <div class="profile-photo">
                        <img src="<?php echo htmlspecialchars($ruta_imagen_usuario) ?>?<?php echo time(); ?>"
                            alt="Foto de perfil" class="profile-image">
                            
                    </div>
                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($nombre); ?></h1>
                        <?php $carrera = obtener_nombre_carrera($usuario_id); ?>
                        <h3>Título: <?php echo htmlspecialchars($carrera); ?></h3>
                        <p class="profile-details">
                            CIP: <?php echo htmlspecialchars($cip); ?> |
                            DNI: <?php echo htmlspecialchars($dni); ?> |
                            DIRECCIÓN: <?php echo htmlspecialchars($direccion); ?> | <br>
                            CELULAR: <?php echo htmlspecialchars($celular); ?>
                    </div>
                </div>
                <?php if (empty($id_curriculum)): ?>

                <!-- Botones -->
                <div class="profile-buttons">
                    <?php $ruta_curriculum = obtener_direccion_curriculum($usuario_id); ?>

                    <a href="../index.php?page=subir_curriculum" target="_blank" class="button blue">Ver Curriculum</a>

                    <a href="../index.php?page=subir_curriculum" class="button gray">Actualizar Curriculum</a>
                </div>

                <?php else: ?>

                <div class="profile-buttons">
                    <?php $ruta_curriculum = obtener_direccion_curriculum($usuario_id); ?>

                    <a href="<?php echo htmlspecialchars($ruta_curriculum); ?>" target="_blank" class="button blue">Ver Curriculum</a>

                    <a href="../index.php?page=actualizar_curriculum" class="button gray">Actualizar Curriculum</a>
                </div>

                <?php endif; ?>

                <!-- Descripción del perfil -->
                <div class="profile-description">
                    <?php $perfil = obtener_perfil_postulante_curriculum($usuario_id); ?>

                    <h3>Descripción del Perfil</h3>
                    <p>
                        <?php echo htmlspecialchars($perfil); ?><br>
                    </p>
                </div>
            </div>
        <?php elseif ($id_rol == 2): ?>
            <!-- Contenedor principal del perfil -->
            <div class="profile-main">
                <!-- Foto de perfil y nombre -->
                <div class="profile-header">
                    <div class="profile-photo">
                        <img src="<?php echo htmlspecialchars($ruta_imagen_usuario) ?>?<?php echo time(); ?>"
                            alt="Foto de perfil" class="profile-image">
                    </div>
                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($nombre); ?></h1>
                        <h3>Empresa</h3>
                        <p class="profile-details">
                            RUC: <?php echo htmlspecialchars($ruc); ?> |
                            REPRESENTANTE: <?php echo htmlspecialchars($representante); ?> | <br>
                            DIRECCIÓN: <?php echo htmlspecialchars($direccion); ?> |
                            CELULAR: <?php echo htmlspecialchars($celular); ?>
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="profile-buttons">
                    <a href="../index.php?page=reporte_propuesta" class="button blue">Mis propuestas</a>
                </div>
            </div>
        <?php elseif ($id_rol == 1): ?>
            <!-- Contenedor principal del perfil -->
            <div class="profile-main">
                <!-- Foto de perfil y nombre -->
                <div class="profile-header">
                    <div class="profile-photo">
                        <img src="<?php echo htmlspecialchars($ruta_imagen_usuario) ?>?<?php echo time(); ?>"
                            alt="Foto de perfil" class="profile-image">
                    </div>
                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($nombre); ?></h1>
                        <h2>Administrador</h2>
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="profile-buttons">
                    <a href="../index.php?page=reportes_admin" class="button blue">Reporte de postulantes</a>
                    <a href="../index.php?page=reportes_empresa_admin" class="button blue">Reporte de empresas</a>
                    <a href="../index.php?page=estadistica" class="button gray">Estadistica</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>