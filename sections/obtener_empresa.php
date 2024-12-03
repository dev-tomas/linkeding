<?php
include("conexion.php");

if (isset($_POST['id_empresa'])) {
    $id_empresa = $_POST['id_empresa'];
    $sql = "SELECT * FROM empresa WHERE id_empresa = $id_empresa";
    $resultado = mysqli_query($cn, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $empresa = mysqli_fetch_assoc($resultado);
        echo json_encode($empresa);
    } else {
        echo json_encode(['error' => 'Empresa no encontrada.']);
    }
}
?>
