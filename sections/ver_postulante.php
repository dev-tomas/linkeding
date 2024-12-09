<?php

require_once 'control/p_ver_postulante.php';
include('control/p_imagen_postulante.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulantes Relacionados</title>
    <link rel="stylesheet" href="../css/ver_postulante.css">
</head>

<body>
    <div class="header-container">
        <h2>Postulantes Relacionados</h2>
        <a href="../index.php?page=reporte_propuesta" class="back-button">Regresar</a>
    </div>
    <table>
        <thead>
            <tr align="center">
                <th>Postulante</th>
                <th>Foto</th>
                <th>Profesión</th>
                <th>Dirección</th>
                <th>Ver Currículum</th>
                <th>Acción</th> <!-- Nueva columna -->
            </tr>
        </thead>

        <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                <tr align="center">

                    <td>
                        <?php echo htmlspecialchars($row['nombre_postulante'] . " " . $row['apellido_paterno_postulante'] . " " . $row['apellido_materno_postulante']); ?>
                    </td>
                    <td>
                        <div class="onlynow-container">

                        <a href="index.php?page=ver_home&id_postulante=<?php echo $row['id_postulante']; ?>">
                            <?php
                            $ruta_imagen_usuario = obtenerImagenPostulante($row['id_postulante']);
                            ?>
                            <img src="<?php echo htmlspecialchars($ruta_imagen_usuario); ?>?<?php echo time(); ?>"
                                alt="Foto de empresa" class="profile-image">
                        </a>
                        </div>
                    </td>

                    <td><?php echo htmlspecialchars($row['profesion_postulante']); ?></td>

                    <td><?php echo htmlspecialchars($row['direccion_postulante']); ?></td>

                    <td>
                        <?php if (!empty($row['id_curriculum'])) { ?>
                            <a href="../curriculum.php?id=<?php echo htmlspecialchars($row['id_curriculum']); ?>"
                                class="curriculum-link">
                                📂 Ver Currículum
                            </a>
                        <?php } else { ?>
                            <span>Currículum no disponible</span>
                        <?php } ?>
                    </td>
                    <td>
    <!-- Asegúrate de pasar TANTO el id_postulante COMO el id_propuesta -->
    <a href="sections/enviar_mensaje.php?id_postulante=<?php echo $row['id_postulante']; ?>&id_propuesta=<?php echo $id_propuesta; ?>" class="btn-contactar">Contactar</a>
</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>