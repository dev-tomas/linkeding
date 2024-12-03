<?php
require_once 'control/p_reporte_propuesta.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Propuestas</title>
    <link href="css/reporte_propuesta.css" rel="stylesheet">
</head>
<body>

<div class="content">
    <div class="header">
        <h2>PROPUESTAS</h2>
        <a href="../index.php?page=propuesta" class="create-button">Crear propuesta</a>
    </div>

    <!-- Agregamos información de depuración -->
    <?php
    // Verificar si $r está definido y es un resultado válido
    if ($r === null) {
        echo "<p>Error: Resultado es NULL</p>";
    } elseif ($r === false) {
        echo "<p>Error en la consulta: " . mysqli_error($cn) . "</p>";
    } else {
        echo "<p>Total de propuestas: " . mysqli_num_rows($r) . "</p>";
    }
    ?>

    <table class="table">
        <thead>
            <tr>
                <th>Nombre Propuesta</th>
                <th>Descripción</th>
                <th>Fecha Límite</th>
                <th>Estado</th>  
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Resetear el puntero del resultado si es necesario
            mysqli_data_seek($r, 0);

            if ($r && mysqli_num_rows($r) > 0) {
                // Imprimir cada fila del resultado
                while ($row = mysqli_fetch_assoc($r)) {
                    // Agregar impresión de depuración
                    echo "<!-- Depuración: ";
                    print_r($row);
                    echo " -->";
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_propuesta']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion_propuesta']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_limite']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_estado_propuesta']); ?></td> 
                    <td>
                        <a href="../index.php?page=editar_propuesta&id=<?php echo $row['id_propuesta']; ?>" class="icon-edit">✎ Editar</a>
                        <a href="../index.php?page=eliminar_propuesta&id=<?php echo $row['id_propuesta']; ?>" class="icon-trash">🗑 Eliminar</a>
                        <a href="../index.php?page=ver_postulante&id=<?php echo $row['id_propuesta']; ?>" class="icon-user">👤 Ver postulantes</a>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5'>No hay propuestas registradas para esta empresa.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>