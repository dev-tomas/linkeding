<?php
include("conexion.php");

$sql = "SELECT p.*, e.nombre_estado_propuesta
FROM propuesta p
INNER JOIN estado_propuesta e ON p.id_estado_propuesta = e.id_estado_propuesta";
$r = mysqli_query($cn, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Propuestas</title>
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<div class="header">
    <div class="logo"></div>
    <div class="nav">
        <a href="propuesta.php">Nueva Propuesta</a>
        <a href="reportepropuesta.php" class="active">Ver Propuestas</a>
    </div>
</div>

<div class="content">
    <h2>Reporte de Propuestas</h2>

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
            while ($row = mysqli_fetch_assoc($r)) {
            ?>
                <tr>
                    <td><?php echo $row['nombre_propuesta']; ?></td>
                    <td><?php echo $row['descripcion_propuesta']; ?></td>
                    <td><?php echo $row['fecha_limite']; ?></td>
                    <td><?php echo $row['nombre_estado_propuesta']; ?></td> 
                    <td>
                        <a href="editarpropuesta.php?id=<?php echo $row['id_estado_propuesta']; ?>" class="icon-edit">✎ Editar</a>
                        <a href="eliminar.php?id=<?php echo $row['id_estado_propuesta']; ?>" class="icon-trash">🗑 Eliminar</a>
                        <a href="verpostulante.php?id=<?php echo $row['id_estado_propuesta']; ?>" class="icon-user">👤 Ver postulantes</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
