<?php

include("conexion.php");


if (isset($_POST['id_comentario'])) {
    $id_comentario = $_POST['id_comentario'];

    $sql_update = "UPDATE mensaje SET id_estado_mensaje=2 WHERE id_mensaje ='$id_comentario'";
    if (mysqli_query($cn, $sql_update)) {
        echo "<script>alert('El comentario ha sido marcado como leído.');window.location.href = '../index.php?page=atencion_empresa';</script>";
    } else {
        echo "<script>alert('Hubo un error al actualizar el estado del comentario.');window.location.href = '../index.php?page=atencion_empresa';</script>";
    }
} else {
    header("Location: ../index.php?page=atencion_empresa");
    exit();
}
