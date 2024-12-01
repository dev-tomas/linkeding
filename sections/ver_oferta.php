<?php
include("conexion.php");

// Verificar si los parámetros están en la URL
if (isset($_GET['empresa']) && isset($_GET['propuesta'])) {
    $empresa = urldecode($_GET['empresa']);
    $propuesta = urldecode($_GET['propuesta']);

    // Consulta para obtener los detalles de la propuesta
    $sql = "select 
            p.descripcion_propuesta, 
            p.requisitos_propuesta, 
            p.fecha_limite, 
            e.razon_social_empresa AS nombre_empresa
        from 
            detalle_empresa_propuesta dep
        inner join
            empresa e ON dep.id_empresa = e.id_empresa
        inner join 
            propuesta p ON dep.id_propuesta = p.id_propuesta
        where 
            e.razon_social_empresa = ? AND p.nombre_propuesta = ?";

    // Preparar consulta segura
    if ($stmt = $cn->prepare($sql)) {
        $stmt->bind_param("ss", $empresa, $propuesta);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar si hay resultados
        if ($r = $resultado->fetch_assoc()) {
            $descripcion = $r['descripcion_propuesta'];
            $requisitos = $r['requisitos_propuesta'];
            $fecha_limite = $r['fecha_limite'];
            
        } else {
            echo "No se encontraron detalles para esta propuesta.";
            exit;
        }
    } 
    
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Propuesta</title>
</head>
<body>
    
    <center><h2><?php echo ($empresa); ?></h2></center>
    <center><h4>PROPUESTA: <?php echo ($propuesta); ?></h4></center>

    <table border="1">
        <tr>
            <td>Descripción</td>
            <td><?php echo ($descripcion); ?></td>
        </tr>
        <tr>
            <td>Requisitos</td>
            <td><?php echo ($requisitos); ?></td>
        </tr>
        <tr>
            <td>Fecha Límite</td>
            <td><?php echo ($fecha_limite); ?></td>
        </tr>
        
        <tr> 
            <form action="" > <!-- No sé a quien va dirigido o quien debe saber mi postulacion -->
            <td colspan="2" align="center">
                <input type="submit" value="Postular" style="width: 200px;">
            </td>
            </form>
        </tr>
    </table>

    
</body>
</html>

