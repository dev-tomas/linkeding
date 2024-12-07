<?php
// p_registro.php
include("../sections/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Validar si el usuario ya existe
    $sql_check = "SELECT * FROM usuario WHERE nombre_usuario = ?";
    $stmt_check = mysqli_prepare($cn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $username);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        echo "El nombre de usuario ya está registrado. Por favor, elige otro.";
    } else {
        // Manejar subida de foto
        $foto_path = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $upload_dir = '../img/usuario/';

            // Obtener la extensión original del archivo
            $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            // Generar el nombre de archivo con el formato especificado
            $foto_name = $username . '_linkeding_foto_usuario_' . '.' . $file_extension;
            $foto_path = $upload_dir . $foto_name;

            // Asegurar que el directorio de subida exista
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Mover archivo subido
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path)) {
                $foto_path = $foto_name;
            } else {
                $foto_path = null;
            }
        }

        // Manejar subida de foto de portada
        $foto_portada_path = null;
        if (isset($_FILES['fotoportada']) && $_FILES['fotoportada']['error'] == 0) {
            $upload_dir = '../img/portada/';

            // Obtener la extensión original del archivo
            $file_extension_portada = strtolower(pathinfo($_FILES['fotoportada']['name'], PATHINFO_EXTENSION));

            // Generar el nombre de archivo con el formato especificado
            $foto_portada_name = $username . '_linkeding_foto_portada_' . '.' . $file_extension_portada;
            $foto_portada_path = $upload_dir . $foto_portada_name;

            // Asegurar que el directorio de subida exista
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Mover archivo subido
            if (move_uploaded_file($_FILES['fotoportada']['tmp_name'], $foto_portada_path)) {
                $foto_portada_path = $foto_portada_name;
            } else {
                $foto_portada_path = null;
            }
        }


        // Registrar al nuevo usuario con foto
        $sql_insert_usuario = "INSERT INTO usuario (nombre_usuario, contrasena_usuario, id_rol, ruta_imagen_usuario, ruta_imagen_portada) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_usuario = mysqli_prepare($cn, $sql_insert_usuario);
        mysqli_stmt_bind_param($stmt_insert_usuario, "ssiss", $username, $password, $rol, $foto_path, $foto_portada_path);

        if (mysqli_stmt_execute($stmt_insert_usuario)) {
            // Obtener el ID del usuario recién insertado
            $user_id = mysqli_insert_id($cn);

            // Insertar detalles adicionales basados en el rol
            if ($rol == 2) { // Empresa
                $razon_social = $_POST['razon_social'];
                $ruc = $_POST['ruc'];
                $celular = $_POST['celular'];
                $direccion = $_POST['direccion'];

                // Concatenar los nombres del representante
                $representante_apellido_paterno = $_POST['representante_apellido_paterno'];
                $representante_apellido_materno = $_POST['representante_apellido_materno'];
                $representante_nombres = $_POST['representante_nombres'];
                $representante = $representante_apellido_paterno . ' ' . $representante_apellido_materno . ' ' . $representante_nombres;

                $sql_insert_empresa = "INSERT INTO empresa (ruc_empresa, razon_social_empresa, celular_empresa, direccion_empresa, representante_empresa, id_estado_empresa, id_usuario) 
                                       VALUES (?, ?, ?, ?, ?, 1, ?)";
                $stmt_insert_empresa = mysqli_prepare($cn, $sql_insert_empresa);
                mysqli_stmt_bind_param($stmt_insert_empresa, "sssssi", $ruc, $razon_social, $celular, $direccion, $representante, $user_id);

                if (!mysqli_stmt_execute($stmt_insert_empresa)) {
                    echo "Error al registrar los detalles de la empresa: " . mysqli_error($cn);
                    exit();
                }

            } elseif ($rol == 3) { // Postulante
                $nombre = $_POST['nombre'];
                $apellido_paterno = $_POST['apellido_paterno'];
                $apellido_materno = $_POST['apellido_materno'];
                $dni = $_POST['dni'];
                $cip = $_POST['cip'];
                $celular = $_POST['celular'];
                $direccion = $_POST['direccion'];
                $fecha_nacimiento = $_POST['fecha_nacimiento'];
                $sexo = $_POST['sexo'];

                $sql_insert_postulante = "INSERT INTO postulante (
                    cip_postulante,
                    dni_postulante, 
                    nombre_postulante, 
                    apellido_paterno_postulante, 
                    apellido_materno_postulante, 
                    celular_postulante, 
                    direccion_postulante, 
                    fecha_nacimiento_postulante, 
                    id_sexo, 
                    id_estado_postulante, 
                    id_usuario
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)"; // El 1 es fijo

                // Ajustar los tipos de datos en el bind_param
                $stmt_insert_postulante = mysqli_prepare($cn, $sql_insert_postulante);
                mysqli_stmt_bind_param(
                    $stmt_insert_postulante,
                    "ssssssssii",
                    $cip,
                    $dni,
                    $nombre,
                    $apellido_paterno,
                    $apellido_materno,
                    $celular,
                    $direccion,
                    $fecha_nacimiento,
                    $sexo,
                    $user_id
                );


                if (!mysqli_stmt_execute($stmt_insert_postulante)) {
                    echo "Error al registrar los detalles del postulante: " . mysqli_error($cn);
                    exit();
                }
            }

            echo "<script>
                alert('Registro de usuario realizado exitosamente');
                window.location.href = '../sections/login.php';
                </script>";
            exit();
        } else {
            echo "Error al registrar el usuario: " . mysqli_error($cn);
        }
    }
}
?>