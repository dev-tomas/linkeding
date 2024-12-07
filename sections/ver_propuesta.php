<?php
require_once 'control/p_ver_propuesta.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferta de Trabajo</title>
    <link rel="stylesheet" href="../css/ver_propuesta.css">
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