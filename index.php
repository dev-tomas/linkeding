<?php
// Coloca esta línea al principio para evitar problemas de salida
ob_start();

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: sections/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MyLinkedIn</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<?php 
include("header.php"); 
include("sections/conexion.php"); 
?>
    <div class="container content mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-4 shadow-lg">
                    <?php
                    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
                    $section_file = "sections/" . $page . ".php";
                    
                    // Verifica si el archivo existe antes de incluirlo
                    if (file_exists($section_file)) {
                        include($section_file);
                    } else {
                        echo "Página no encontrada";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
<?php
// Finaliza el buffer de salida
ob_end_flush();
?>