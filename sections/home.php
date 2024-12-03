<?php
require_once 'control/p_home.php';
?>
<div class="home-content">
    <h1>Bienvenido(a), <?php echo htmlspecialchars($nombre); ?> 😄</h1>
    <hr>
    <h3>Perfil</h3>
    <?php if ($id_rol == 3): ?>
        <!-- POSTULANTE -->
        <p><strong>CIP:</strong> <?php echo htmlspecialchars($cip); ?></p>
        <p><strong>DNI:</strong> <?php echo htmlspecialchars($dni); ?></p>
        <p><strong>Nombres:</strong> <?php echo htmlspecialchars($nombre); ?></p>
        <p><strong>Apellidos:</strong> <?php echo htmlspecialchars($apellido_paterno . ' ' . $apellido_materno); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($nombre_estado_postulante); ?></p>
        <?php if (strtolower($estado) !== '1'): ?>
            <div class="alert alert-warning">
                ⚠️ Su estado no está activo, por favor contacte con soporte.
            </div>
        <?php endif; ?>
    <?php elseif ($id_rol == 2): ?>
        <!-- EMPRESA -->
        <p><strong>Razón Social:</strong> <?php echo htmlspecialchars($razon_social); ?></p>
        <p><strong>Representante:</strong> <?php echo htmlspecialchars($representante); ?></p>
        <p><strong>RUC:</strong> <?php echo htmlspecialchars($ruc); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($nombre_estado_empresa); ?></p>
        <?php if (strtolower($estado) !== '1'): ?>
            <div class="alert alert-warning">
                ⚠️ Su estado no está activo, por favor contacte con soporte.
            </div>
        <?php endif; ?>
    <?php elseif ($id_rol == 1): ?>
        <!-- ADMINISTRADOR -->
        <p><strong>Nombres:</strong> <?php echo htmlspecialchars($nombre); ?></p>
        <p><strong>Apellidos:</strong> <?php echo htmlspecialchars($apellido_paterno . ' ' . $apellido_materno); ?></p>
    <?php else: ?>
        <!-- Caso no identificado -->
        <div class="alert alert-danger">
            ❌ Rol no identificado. Por favor contacte con soporte.
        </div>
    <?php endif; ?>
</div>
