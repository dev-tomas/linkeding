<?php
include("conexion.php");

// Verificar si los parámetros están en la URL
if (isset($_GET['empresa']) && isset($_GET['propuesta'])) {
    $empresa = urldecode($_GET['empresa']);
    $propuesta = urldecode($_GET['propuesta']);

    // Consulta para obtener los detalles de la propuesta
    $sql = "SELECT 
                p.descripcion_propuesta, 
                p.requisitos_propuesta, 
                p.fecha_limite, 
                e.razon_social_empresa AS nombre_empresa,
                p.id_propuesta
            FROM 
                detalle_empresa_propuesta dep
            INNER JOIN
                empresa e ON dep.id_empresa = e.id_empresa
            INNER JOIN 
                propuesta p ON dep.id_propuesta = p.id_propuesta
            WHERE 
                e.razon_social_empresa = ? AND p.nombre_propuesta = ?";

    // Preparar consulta segura
    if ($fila = $cn->prepare($sql)) {
        $fila->bind_param("ss", $empresa, $propuesta);
        $fila->execute();
        $resultado = $fila->get_result();

        // Verificar si hay resultados
        if ($r = $resultado->fetch_assoc()) {
            $descripcion = $r['descripcion_propuesta'];
            $requisitos = $r['requisitos_propuesta'];
            $fecha_limite = $r['fecha_limite'];
            $id_propuesta = $r['id_propuesta']; // Obtener el ID de la propuesta
        } else {
            echo "No se encontraron detalles para esta propuesta.";
            exit;
        }
    }
} else {
    echo "Parámetros inválidos.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Propuesta</title>
    <link rel="stylesheet" href="css/ver_oferta.css">
</head>
<body>
    
    <center><h2><strong><?php echo htmlspecialchars($empresa); ?></strong></h2></center>
    <center><h5>PROPUESTA: <?php echo htmlspecialchars($propuesta); ?></h5></center>

    <form action="../linkeding/control/p_ver_oferta.php" method="POST">
        
    <input type="hidden" name="id_propuesta" value="<?php echo htmlspecialchars($id_propuesta); ?>">
        
        <table border="1">
            <tr>
                <td>Descripción</td>
                <td><?php echo htmlspecialchars($descripcion); ?></td>
            </tr>
            <tr>
                <td>Requisitos</td>
                <td><?php echo htmlspecialchars($requisitos); ?></td>
            </tr>
            <tr>
                <td>Fecha Límite</td>
                <td><?php echo htmlspecialchars($fecha_limite); ?></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="POSTULAR">
                </td>
            </tr>
        </table>
        
    </form>
</body>
</html>

