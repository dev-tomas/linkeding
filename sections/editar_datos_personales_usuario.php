<?php
include("conexion.php");

$cod = isset($_SESSION["usuario_id"]) ? (int)$_SESSION["usuario_id"] : 0;
$tipo_usuario = isset($_SESSION["id_rol"]) ? (int)$_SESSION["id_rol"] : 0;
$message = "";
$data = [];
$errors = [];

// Funciones de validación
function validateInput($data, $type) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($type === 'int') {
        return (int)$data;
    }
    return $data;
}

function validateNumericField($data, $length, $fieldName) {
    if (!is_numeric($data) || strlen($data) !== $length) {
        return "El campo '$fieldName' debe contener $length dígitos numéricos.";
    }
    return null;
}

function capitalizeFirstLetter($str) {
    if (empty($str)) return "";
    return strtoupper(substr($str, 0, 1)) . substr($str, 1);
}

// Función para mostrar errores
function displayErrors($errors) {
    if (!empty($errors)) {
        $html = "<ul style='color:red;'>";
        foreach ($errors as $error) {
            $html .= "<li>$error</li>";
        }
        $html .= "</ul>";
        return $html;
    }
    return "";
}

// Consulta inicial para obtener datos del usuario
try {
    $query = "";
    if ($tipo_usuario == 1) {
        $query = "SELECT * FROM administrador WHERE id_usuario = ?";
    } elseif ($tipo_usuario == 2) {
        $query = "SELECT * FROM empresa WHERE id_usuario = ?";
    } elseif ($tipo_usuario == 3) {
        $query = "SELECT * FROM postulante WHERE id_usuario = ?";
    } else {
        $message = "Tipo de usuario no válido.";
    }

    if (!empty($query)) {
        $stmt = $cn->prepare($query);
        $stmt->bind_param("i", $cod);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
    }
} catch (Exception $e) {
    $message = "Error en la consulta a la base de datos: " . $e->getMessage();
}

if (!$data && empty($message)) {
    $message = "No se encontraron datos para el usuario.";
}

if (isset($_POST['submit'])) {
    try {
        if ($tipo_usuario == 1) { // Administrador
            $nombre_admin = capitalizeFirstLetter(validateInput($_POST['nombre_administrador'], 'string'));
            $apaterno_admin = capitalizeFirstLetter(validateInput($_POST['apellido_paterno_administrador'], 'string'));
            $amaterno_admin = capitalizeFirstLetter(validateInput($_POST['apellido_materno_administrador'], 'string'));
            $SexoAdmin = capitalizeFirstLetter(validateInput($_POST['id_sexo'],'int'));

            $errors = []; // Reiniciar array de errores
            if (empty($nombre_admin)) $errors[] = "El nombre es requerido";
            if (empty($apaterno_admin)) $errors[] = "El apellido paterno es requerido";
            if (empty($amaterno_admin)) $errors[] = "El apellido materno es requerido";
            if(empty($SexoAdmin)) $error[] = "El Sexo es requerido";


            if (empty($errors)) {
                $stmt = $cn->prepare("UPDATE administrador SET nombre_administrador = ?, apellido_paterno_administrador = ?, apellido_materno_administrador = ?, id_sexo = ? WHERE id_usuario = ?");
                $stmt->bind_param("sssii", $nombre_admin, $apaterno_admin, $amaterno_admin, $SexoAdmin,$cod);
                $stmt->execute();
                $message = "Administrador actualizado correctamente.";
                header("Location: index.php");
                exit;
            } else {
                $message = displayErrors($errors);
            }
        } elseif ($tipo_usuario == 2) { // Empresa
            $razon_social_empresa = capitalizeFirstLetter(validateInput($_POST['razon_social_empresa'], 'string'));
            $celular_empresa = validateInput($_POST['celular_empresa'], 'string');
            $direccion_empresa = validateInput($_POST['direccion_empresa'], 'string');
            $representante_empresa = capitalizeFirstLetter(validateInput($_POST['representante_empresa'], 'string'));

            $errors = [];
            if (empty($razon_social_empresa)) $errors[] = "La razón social es requerida";
            if (empty($celular_empresa)) $errors[] = "El celular es requerido";
            if (empty($direccion_empresa)) $errors[] = "La dirección es requerida";
            if (empty($representante_empresa)) $errors[] = "El representante es requerido";
            $celularError = validateNumericField($celular_empresa, 9, 'Celular');
            if ($celularError) $errors[] = $celularError;

            if (empty($errors)) {
                $stmt = $cn->prepare("UPDATE empresa SET razon_social_empresa = ?, celular_empresa = ?, direccion_empresa = ?, representante_empresa = ? WHERE id_usuario = ?");
                $stmt->bind_param("ssssi", $razon_social_empresa, $celular_empresa, $direccion_empresa, $representante_empresa, $cod);
                $stmt->execute();
                $message = "Empresa actualizada correctamente.";
                header("Location: index.php");
                exit;
            } else {
                $message = displayErrors($errors);
            }
        } elseif ($tipo_usuario == 3) { // Postulante
            $celular_postulante = validateInput($_POST['celular_postulante'], 'string');
            $direccion_postulante = validateInput($_POST['direccion_postulante'], 'string');

            $errors = [];
            if (empty($celular_postulante)) $errors[] = "El celular es requerido";
            if (empty($direccion_postulante)) $errors[] = "La dirección es requerida";
            $celularError = validateNumericField($celular_postulante, 9, 'Celular');
            if ($celularError) $errors[] = $celularError;

            if (empty($errors)) {
                $stmt = $cn->prepare("UPDATE postulante SET celular_postulante = ?, direccion_postulante = ? WHERE id_usuario = ?");
                $stmt->bind_param("ssi", $celular_postulante, $direccion_postulante, $cod);
                $stmt->execute();
                $message = "Postulante actualizado correctamente.";
                header("Location: index.php");
                exit;
            } else {
                $message = displayErrors($errors);
            }
        }
    } catch (Exception $e) {
        $message = "Error al actualizar los datos: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Datos</title>
    <link rel="stylesheet" href="css/editar_usuario.css">
</head>
<body>
    <div class="container">
        <h1>Editar Datos Personales</h1>
        <?php echo $message; // Mostrar mensajes de éxito o error ?>
        <form action="" method="post">
            <?php if ($tipo_usuario == 1): ?>
                <!-- Formulario para Administrador -->
                <table border="1" cellpadding="10">
                
                    <tr><th>Nombre:</th><td><input type="text" name="nombre_administrador" value="<?php echo $data['nombre_administrador'] ?? ''; ?>" required></td></tr>
                    <tr><th>Apellido Paterno:</th><td><input type="text" name="apellido_paterno_administrador" value="<?php echo $data['apellido_paterno_administrador'] ?? ''; ?>" required></td></tr>
                    <tr><th>Apellido Materno:</th><td><input type="text" name="apellido_materno_administrador" value="<?php echo $data['apellido_materno_administrador'] ?? ''; ?>" required></td></tr>
                    <tr><th>Sexo:</th><td>
                         <select name="id_sexo" required>
                             <?php 
                            $sql_sexos = "SELECT id_sexo, nombre_sexo FROM sexo";
                            $result_sexos = mysqli_query($cn, $sql_sexos);
                            while ($row_sexo = mysqli_fetch_assoc($result_sexos)) {
                                $selected = ($data['id_sexo'] == $row_sexo['id_sexo']) ? 'selected' : '';
                                echo "<option value='" . $row_sexo['id_sexo'] . "' $selected>" . $row_sexo['nombre_sexo'] . "</option>";
                                }
                            ?>
                             </select>

                      </td>



                </table>
            <?php elseif ($tipo_usuario == 2): ?>
                <!-- Formulario para Empresa -->
                <table border="1" cellpadding="10">
                    <tr><th>Razón Social:</th><td><input type="text" name="razon_social_empresa" value="<?php echo $data['razon_social_empresa'] ?? ''; ?>" required></td></tr>
                    <tr><th>Celular:</th><td><input type="tel" name="celular_empresa" value="<?php echo $data['celular_empresa'] ?? ''; ?>" maxlength="9" required></td></tr>
                    <tr><th>Dirección:</th><td><input type="text" name="direccion_empresa" value="<?php echo $data['direccion_empresa'] ?? ''; ?>" required></td></tr>
                    <tr><th>Representante:</th><td><input type="text" name="representante_empresa" value="<?php echo $data['representante_empresa'] ?? ''; ?>" required></td></tr>
                </table>
            <?php elseif ($tipo_usuario == 3): ?>
                <!-- Formulario para Postulante -->
                <table border="1" cellpadding="10">
                    <tr><th>Celular:</th><td><input type="tel" name="celular_postulante" value="<?php echo $data['celular_postulante'] ?? ''; ?>" maxlength="9" required></td></tr>
                    <tr><th>Dirección:</th><td><input type="text" name="direccion_postulante" value="<?php echo $data['direccion_postulante'] ?? ''; ?>" required></td></tr>
                </table>
            <?php endif; ?>
            <br>
            <button type="submit" name="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>