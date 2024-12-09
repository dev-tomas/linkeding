<?php
include __DIR__ . '/../control/p_ver_home.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Empresa - LINKEDING</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <?php
    if ($id_rol == 2) {
        ?>
        <div class="linkeding-home">
            <!-- El resto del HTML permanece igual -->
            <!-- Sección de Portada -->
            <div class="profile-cover">
                <img src="<?php echo htmlspecialchars($ruta_imagen_portada) ?>?<?php echo time(); ?>" alt="Portada"
                    class="cover-image">
            </div>

            <!-- Contenedor principal del perfil de EMPRESA -->
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

                <!-- Botones para empresa visitada -->
                <div class="profile-buttons">
                    <a href="index.php?page=ver_propuestas_empresa&id_empresa=<?php echo $id_empresa_visitada; ?>"
                        class="button blue">Ver Propuestas</a>
                    <a href="index.php?page=contactar_empresa&id_empresa=<?php echo $id_empresa_visitada; ?>"
                        class="button report">Reportar</a>
                </div>
            </div>
        </div>
    </body>

    </html>


    <?php
    } elseif ($id_rol == 3) {
        ?>
    <div class="linkeding-home">
        <!-- Sección de Portada -->
        <div class="profile-cover">
            <img src="<?php echo htmlspecialchars($ruta_imagen_portada) ?>?<?php echo time(); ?>" alt="Portada"
                class="cover-image">
        </div>
        <div class="profile-main">
            <!-- Foto de perfil y nombre -->
            <div class="profile-header">
                <div class="profile-photo">
                    <img src="<?php echo htmlspecialchars($ruta_imagen_usuario) ?>?<?php echo time(); ?>"
                        alt="Foto de perfil" class="profile-image">

                </div>
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($nombre); ?></h1>
                    <?php $carrera = obtener_nombre_carrera($id_postulante_visitado); ?>
                    <h3>Título: <?php echo htmlspecialchars($carrera); ?></h3>
                    <p class="profile-details">
                        CIP: <?php echo htmlspecialchars($cip); ?> |
                        DNI: <?php echo htmlspecialchars($dni); ?> |
                        DIRECCIÓN: <?php echo htmlspecialchars($direccion); ?> | <br>
                        CELULAR: <?php echo htmlspecialchars($celular); ?>
                </div>
            </div>
            <?php if (empty($curriculum)): ?>
                <!-- Botones -->
                <div class="profile-buttons">
                    <?php $ruta_curriculum = obtener_direccion_curriculum($id_postulante_visitado); ?>

                    <a href="index.php?page=queja&id_empresa=<?php echo $id_empresa_visitada; ?>"
                        class="button report">Reportar</a>
                </div>

            <?php else: ?>

                <div class="profile-buttons">
                    <?php $ruta_curriculum = obtener_direccion_curriculum($id_postulante_visitado); ?>

                    <a href="<?php echo htmlspecialchars($ruta_curriculum); ?>" target="_blank" class="button blue">Ver
                        Curriculum</a>

                    <a href="index.php?page=queja&id_postulante=<?php echo $id_postulante_visitado; ?>"
                        class="button report">Reportar</a>
                </div>

            <?php endif; ?>

            <!-- Descripción del perfil -->
            <div class="profile-description">
                <?php $perfil = obtener_perfil_postulante_curriculum($id_postulante_visitado); ?>

                <h3>Descripción del Perfil</h3>
                <p>
                    <?php echo htmlspecialchars($perfil); ?><br>
                </p>
            </div>
        </div>

        <?php
    } else {
        ?>
        <h1>No habían datos XD</h1>
        <?php
    }
    ?>