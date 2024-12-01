<?php
require_once 'control/p_home.php';
?>
<div class="home-content">
    <h1>Bienvenido(a), <?php echo htmlspecialchars($nombre); ?> 😄</h1>
    <hr>
    <h3>Perfil</h3>

    <!-- Si el usuario es un postulante -->
    <?php if ($id_rol == 3): ?>
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

    <!-- Si el usuario es una empresa -->
    <?php elseif ($id_rol == 2): ?>
        <p><strong>Razón Social:</strong> <?php echo htmlspecialchars($razon_social); ?></p>
        <p><strong>Representante:</strong> <?php echo htmlspecialchars($representante); ?></p>
        <p><strong>RUC:</strong> <?php echo htmlspecialchars($ruc); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($nombre_estado_empresa); ?></p>
        <?php if (strtolower($estado) !== '1'): ?>
            <div class="alert alert-warning">
                ⚠️ Su estado no está activo, por favor contacte con soporte.
            </div>
        <?php endif; ?>

    <!-- Si el usuario es un administrador -->
    <?php elseif ($id_rol == 1): ?>
        <p><strong>Nombres:</strong> <?php echo htmlspecialchars($nombre); ?></p>
        <p><strong>Apellidos:</strong> <?php echo htmlspecialchars($apellido_paterno . ' ' . $apellido_materno); ?></p>
        <p><strong>Rol:</strong> Administrador</p>

    <!-- Si el usuario no tiene un rol válido -->
    <?php else: ?>
        <p>No se encontró información para este usuario.</p>
    <?php endif; ?>
</div>
