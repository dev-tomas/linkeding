<?php
include("conexion.php"); // Incluimos el archivo de conexión

// ID de la propuesta que quieres consultar
$cod = isset($_GET['id_propuesta']) ? $_GET['id_propuesta'] : ''; // Obtén el ID de la propuesta desde el parámetro GET

$r = []; // Inicializamos la variable $r como un array vacío

// Verifica que $cod no esté vacío
if (!empty($cod)) {
    // Consulta SQL para obtener los datos de las tablas relacionadas
    $sql = "SELECT p.id_propuesta, 
                   p.nombre_propuesta, 
                   p.descripcion_propuesta, 
                   p.requisitos_propuesta, 
                   p.fecha_limite, 
                   ep.nombre_estado_propuesta
            FROM propuesta p
            INNER JOIN estado_propuesta ep ON p.id_estado_propuesta = ep.id_estado_propuesta
            WHERE p.id_propuesta = 1";

    $f = mysqli_query($cn, $sql); // Ejecutamos la consulta
    if ($f && mysqli_num_rows($f) > 0) {
        $r = mysqli_fetch_assoc($f); // Asociamos el resultado
    } else {
        echo "No se encontraron datos para la propuesta seleccionada.";
    }
} else {
    echo "El código de propuesta no fue proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferta de Trabajo</title>
    <link rel="stylesheet" href="css/stilo.css">
</head>
<body>

    <header class="header">
        <h1>Desarrollador PHP CodeIgniter</h1>
        <p><a href="#">Otra Empresa</a> como <span class="freelance">Freelance</span></p>
    </header>

    <section class="company-overview">
        <h2>Company Overview</h2>
        <div class="overview-content">
            <img src="img/logo.jpg" alt="Company Logo">
        </div>
    </section>

    <section class="job-description">
        <h2>DESCRIPCION</h2>
        <p><?php echo isset($r['descripcion_propuesta']) ? $r['descripcion_propuesta'] : 'No disponible'; ?></p>
    </section>
    
    <section class="job-description">
        <h2>REQUISITOS</h2>
        <p><?php echo isset($r['requisitos_propuesta']) ? $r['requisitos_propuesta'] : 'No disponible'; ?></p>
    </section>
    
    <section class="job-description">
        <h2>FECHA LIMITE</h2>
        <p><?php echo isset($r['fecha_limite']) ? $r['fecha_limite'] : 'No disponible'; ?></p>
    </section>
    
    <center>
        <button class="apply-btn">APPLY THIS JOB</button>
    </center>
</body>
</html>
