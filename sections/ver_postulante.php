<?php
include("conexion.php");

// Obtén el ID de la propuesta desde la URL
$id_propuesta = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener los postulantes de la propuesta
$sql = "
    SELECT 
        p.nombre_postulante, 
        p.apellido_paterno_postulante, 
        p.apellido_materno_postulante, 
        p.celular_postulante, 
        p.direccion_postulante, 
        p.fecha_nacimiento_postulante,
        c.nombre_carrera AS profesion_postulante, 
        p.id_curriculum,
        dp.fecha_postulacion
        
    FROM detalle_postulante_propuesta dp
    INNER JOIN postulante p ON dp.id_postulante = p.id_postulante
    INNER JOIN curriculum cu ON cu.id_curriculum = p.id_curriculum
    INNER JOIN carrera c ON c.id_carrera = cu.id_carrera
    WHERE dp.id_propuesta = $id_propuesta
";

$resultado = mysqli_query($cn, $sql);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($cn));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulantes Relacionados</title>
    <link rel="stylesheet" href="../css/verpostulante.css"> 
</head>
<body>
    <h2>Postulantes Relacionados</h2>

    
    <table>
        
        <thead>
            <tr align="center">
                <th>Postulante</th>
                <th>Foto</th>
                <th>Profesión</th>
                <th>Dirección</th>
                <th>Ver Currículum</th>
            </tr>
        </thead>
        
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                <tr align="center">
                   
                    <td>
                        <?php echo htmlspecialchars($row['nombre_postulante'] . " " . $row['apellido_paterno_postulante'] . " " . $row['apellido_materno_postulante']); ?>
                    </td>
                    
                    <td>
                        <img 
                            src="../img/<?php echo !empty($row['foto_postulante']) ? htmlspecialchars($row['foto_postulante']) : 'user.svg'; ?>" 
                            alt="Foto de <?php echo htmlspecialchars($row['nombre_postulante']); ?>" 
                            class="postulante-foto">
                    </td>
                    
                    <td><?php echo htmlspecialchars($row['profesion_postulante']); ?></td>
                    
                    <td><?php echo htmlspecialchars($row['direccion_postulante']); ?></td>
                   
                    <td>
                        <?php if (!empty($row['id_curriculum'])) { ?>
                            <a href="../curriculum.php?id=<?php echo htmlspecialchars($row['id_curriculum']); ?>" class="curriculum-link">
                                📂 Ver Currículum
                            </a>
                        <?php } else { ?>
                            <span>Currículum no disponible</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

   
    <a href="../index.php?page=reporte_propuesta" class="back-button">Regresar</a>
</body>
</html>