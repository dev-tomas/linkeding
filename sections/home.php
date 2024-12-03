<?php
require_once 'control/p_home.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LINKEDING - Perfil</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="linkeding-home">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-main-info">
                    <div class="profile-image-container">
                        <img src="<?php echo htmlspecialchars($ruta_imagen_usuario); ?>" alt="Foto de perfil" class="profile-image">
                    </div>
                    <div class="profile-name-section">
                        <h1><?php echo htmlspecialchars($nombre); ?></h1>
                        <?php if ($id_rol == 3): ?>
                            <p class="profile-subtitle">Postulante</p>
                        <?php elseif ($id_rol == 2): ?>
                            <p class="profile-subtitle">Empresa</p>
                        <?php elseif ($id_rol == 1): ?>
                            <p class="profile-subtitle">Administrador</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="profile-details">
                <?php if ($id_rol == 3): ?>
                    <div class="detail-card">
                        <h3>Información Personal</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">CIP</span>
                                <span class="detail-value"><?php echo htmlspecialchars($cip); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">DNI</span>
                                <span class="detail-value"><?php echo htmlspecialchars($dni); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Nombres</span>
                                <span class="detail-value"><?php echo htmlspecialchars($nombre); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Apellidos</span>
                                <span class="detail-value"><?php echo htmlspecialchars($apellido_paterno . ' ' . $apellido_materno); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Dirección postulante</span>
                                <span class="detail-value"><?php echo htmlspecialchars($direccion); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if (strtolower($estado) !== '1'): ?>
                        <div class="alert alert-warning">
                            ⚠️ Su estado no está activo, por favor contacte con soporte.
                        </div>
                    <?php endif; ?>

                <?php elseif ($id_rol == 2): ?>
                    <div class="detail-card">
                        <h3>Información de Empresa</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Razón Social</span>
                                <span class="detail-value"><?php echo htmlspecialchars($razon_social); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Representante</span>
                                <span class="detail-value"><?php echo htmlspecialchars($representante); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">RUC</span>
                                <span class="detail-value"><?php echo htmlspecialchars($ruc); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Estado</span>
                                <span class="detail-value"><?php echo htmlspecialchars($nombre_estado_empresa); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if (strtolower($estado) !== '1'): ?>
                        <div class="alert alert-warning">
                            ⚠️ Su estado no está activo, por favor contacte con soporte.
                        </div>
                    <?php endif; ?>

                <?php elseif ($id_rol == 1): ?>
                    <div class="detail-card">
                        <h3>Información de Administrador</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Nombres</span>
                                <span class="detail-value"><?php echo htmlspecialchars($nombre); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Apellidos</span>
                                <span class="detail-value"><?php echo htmlspecialchars($apellido_paterno . ' ' . $apellido_materno); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Rol</span>
                                <span class="detail-value">Administrador</span>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <p>No se encontró información para este usuario.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>