<?php
include("../sections/conexion.php");
session_start();


if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nombre_usuario'])) {
    echo "<script>
            alert('No se pudo identificar al usuario. Inicia sesión nuevamente.');
            window.location.href = '../sections/login.php';
          </script>";
    exit();
}


$user_id = $_SESSION['usuario_id'];
$username = $_SESSION['nombre_usuario'];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $upload_dir_user = '../img/usuario/';
    $upload_dir_portada = '../img/portada/';

    
    $new_foto_path = null;
    $new_foto_portada_path = null;

    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $foto_name = $username . '_linkeding_foto_usuario_.' . $file_extension;
        $new_foto_path = $upload_dir_user . $foto_name;

        
        if (!is_dir($upload_dir_user)) {
            mkdir($upload_dir_user, 0755, true);
        }

        
        $existing_foto = glob($upload_dir_user . $username . '_linkeding_foto_usuario_*');
        if (!empty($existing_foto)) {
            unlink($existing_foto[0]); 
        }

        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $new_foto_path)) {
            $new_foto_path = $foto_name; 
        } else {
            $new_foto_path = null;
        }
    }

    
    if (isset($_FILES['fotoportada']) && $_FILES['fotoportada']['error'] == 0) {
        $file_extension = strtolower(pathinfo($_FILES['fotoportada']['name'], PATHINFO_EXTENSION));
        $foto_portada_name = $username . '_linkeding_foto_portada_.' . $file_extension;
        $new_foto_portada_path = $upload_dir_portada . $foto_portada_name;

      
        if (!is_dir($upload_dir_portada)) {
            mkdir($upload_dir_portada, 0755, true);
        }

        
        $existing_foto_portada = glob($upload_dir_portada . $username . '_linkeding_foto_portada_*');
        if (!empty($existing_foto_portada)) {
            unlink($existing_foto_portada[0]); 
        }

        
        if (move_uploaded_file($_FILES['fotoportada']['tmp_name'], $new_foto_portada_path)) {
            $new_foto_portada_path = $foto_portada_name; 
        } else {
            $new_foto_portada_path = null;
        }
    }

    
    $sql_update = "UPDATE usuario 
                   SET ruta_imagen_usuario = COALESCE(?, ruta_imagen_usuario), 
                       ruta_imagen_portada = COALESCE(?, ruta_imagen_portada) 
                   WHERE id_usuario = ?";
    $stmt_update = mysqli_prepare($cn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ssi", $new_foto_path, $new_foto_portada_path, $user_id);

    if (mysqli_stmt_execute($stmt_update)) {
        echo "<script>
                alert('Fotos actualizadas correctamente');
                window.location.href = '../index.php?page=home';
              </script>";
    } else {
        echo "Error al actualizar las fotos: " . mysqli_error($cn);
    }
}
?>
