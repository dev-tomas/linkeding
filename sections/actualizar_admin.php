<?php
include("conexion.php");

if (isset($_POST['id_administrador'])) {
    $id_administrador = $_POST['id_administrador'];
    $nombre_admin = $_POST['nombre_administrador'];
    $apaterno_admin= $_POST['apellido_paterno_administrador'];
    $amaterno_admin= $_POST['apellido_materno_administrador'];
    $SexoAdmin= $_POST['id_sexo'];

    $sql_update = "UPDATE administrador SET 
                   nombre_administrador = '$celular_postulante',
                   apellido_paterno_administrador= '$apaterno_admin',
                   apellido_materno_administrador= '$amaterno_admin',
                   id_sexo= '$SexoAdmin'
                   WHERE id_administrador = $id_administrador";

    if (mysqli_query($cn, $sql_update)) {
            header("Location: ../index.php?page=reportes_admin");
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar sus datos: ' . mysqli_error($cn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos para actualizar.']);
}
?>
