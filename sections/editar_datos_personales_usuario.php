<?php
include("conexion.php");

$cod = (int) $_SESSION["usuario_id"];
$tipo_usuario = (int) $_SESSION["id_rol"];

// Validación de entrada
function validateInput($data, $type) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($type == 'int') {
        return (int)$data;
    }
    return $data;
}

// Función para validar campos numéricos con longitud específica
function validateNumericField($data, $length, $fieldName) {
    if (!is_numeric($data) || strlen($data) !== $length) {
        return "El campo '$fieldName' debe contener $length dígitos numéricos.";
    }
    return null;
}

// Función para capitalizar solo la primera letra
function capitalizeFirstLetter($str) {
    if (empty($str)) return "";
    return strtoupper(substr($str, 0, 1)) . substr($str, 1);
}


// Consulta inicial para obtener datos del usuario
$message = "";
if ($tipo_usuario == 2) { // Empresa
    $stmt = $cn->prepare("SELECT * FROM empresa WHERE id_usuario = ?");
    if ($stmt === false) {
        $message = "Error al preparar la consulta (empresa): " . $cn->error;
    } else {
        $stmt->bind_param("i", $cod);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
    }
} elseif ($tipo_usuario == 3) { // Postulante
    $stmt = $cn->prepare("SELECT * FROM postulante WHERE id_usuario = ?");
    if ($stmt === false) {
        $message = "Error al preparar la consulta (postulante): " . $cn->error;
    } else {
        $stmt->bind_param("i", $cod);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
    }
} else {
    $message = "Tipo de usuario no válido.";
}

if (!$data && empty($message)) {
    $message = "No se encontraron datos para el usuario.";
}

if (isset($_POST['submit'])) {
    $errors = [];

    if ($tipo_usuario == 2) { // Empresa
        $ruc_empresa = validateInput($_POST['ruc_empresa'], 'string');
        $razon_social_empresa = capitalizeFirstLetter(validateInput($_POST['razon_social_empresa'], 'string'));
        $celular_empresa = validateInput($_POST['celular_empresa'], 'string');
        $direccion_empresa = validateInput($_POST['direccion_empresa'], 'string');
        $representante_empresa = capitalizeFirstLetter(validateInput($_POST['representante_empresa'], 'string'));

        $rucError = validateNumericField($_POST['ruc_empresa'], 11, 'RUC');
        if ($rucError) $errors[] = $rucError;
        $celularError = validateNumericField($_POST['celular_empresa'], 9, 'Celular');
        if ($celularError) $errors[] = $celularError;

        if (empty($errors)) {
            $stmt = $cn->prepare("UPDATE empresa SET ruc_empresa = ?, razon_social_empresa = ?, celular_empresa = ?, direccion_empresa = ?, representante_empresa = ? WHERE id_usuario = ?");
            if ($stmt === false) {
                $message = "Error al preparar la consulta (empresa): " . $cn->error;
            } else {
                $stmt->bind_param("sssssi", $ruc_empresa, $razon_social_empresa, $celular_empresa, $direccion_empresa, $representante_empresa, $cod);
                if ($stmt->execute()) {
                    $message = "Empresa actualizada correctamente.";
                    header("Location: index.php");
                    exit;
                } else {
                    $message = "Error al actualizar la empresa: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $message = "<ul style='color:red;'>";
            foreach ($errors as $error) {
                $message .= "<li>$error</li>";
            }
            $message .= "</ul>";
        }

    } elseif ($tipo_usuario == 3) { // Postulante
        $cip_postulante = validateInput($_POST['cip_postulante'], 'string');
        $dni_postulante = validateInput($_POST['dni_postulante'], 'string');
        $nombre_postulante = capitalizeFirstLetter(validateInput($_POST['nombre_postulante'], 'string'));
        $apellido_paterno_postulante = capitalizeFirstLetter(validateInput($_POST['apellido_paterno_postulante'], 'string'));
        $apellido_materno_postulante = capitalizeFirstLetter(validateInput($_POST['apellido_materno_postulante'], 'string'));
        $celular_postulante = validateInput($_POST['celular_postulante'], 'string');
        $direccion_postulante = validateInput($_POST['direccion_postulante'], 'string');
        $fecha_nacimiento_postulante = validateInput($_POST['fecha_nacimiento_postulante'], 'string');

        $cipError = validateNumericField($_POST['cip_postulante'], 8, 'CIP');
        if ($cipError) $errors[] = $cipError;
        $dniError = validateNumericField($_POST['dni_postulante'], 8, 'DNI');
        if ($dniError) $errors[] = $dniError;
        $celularError = validateNumericField($_POST['celular_postulante'], 9, 'Celular');
        if ($celularError) $errors[] = $celularError;

        if (empty($errors)) {
            $stmt = $cn->prepare("UPDATE postulante SET cip_postulante = ?, dni_postulante = ?, nombre_postulante = ?, apellido_paterno_postulante = ?, apellido_materno_postulante = ?, celular_postulante = ?, direccion_postulante = ?, fecha_nacimiento_postulante = ? WHERE id_usuario = ?");
            if ($stmt === false) {
                $message = "Error al preparar la consulta (postulante): " . $cn->error;
            } else {
                $stmt->bind_param("ssssssssi", $cip_postulante, $dni_postulante, $nombre_postulante, $apellido_paterno_postulante, $apellido_materno_postulante, $celular_postulante, $direccion_postulante, $fecha_nacimiento_postulante, $cod);
                if ($stmt->execute()) {
                    $message = "Postulante actualizado correctamente.";
                    header("Location: index.php");
                    exit;
                } else {
                    $message = "Error al actualizar el postulante: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $message = "<ul style='color:red;'>";
            foreach ($errors as $error) {
                $message .= "<li>$error</li>";
            }
            $message .= "</ul>";
        }
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
        <?php if (!empty($message)): ?>
            <p class="<?php echo strpos(strtolower($message), 'error') !== false ? 'error' : 'success'; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <?php if ($tipo_usuario == 3): ?>
                <!-- Formulario para Postulante -->
                <table border="1" cellpadding="10">
                    <tr><th>CIP:</th><td><input type="text" name="cip_postulante" value="<?php echo $data['cip_postulante'] ?? ''; ?>" maxlength="8" required></td></tr>
                    <tr><th>DNI:</th><td><input type="text" name="dni_postulante" value="<?php echo $data['dni_postulante'] ?? ''; ?>" maxlength="8" required></td></tr>
                    <tr><th>Nombre:</th><td><input type="text" name="nombre_postulante" value="<?php echo $data['nombre_postulante'] ?? ''; ?>" required></td></tr>
                    <tr><th>Apellido Paterno:</th><td><input type="text" name="apellido_paterno_postulante" value="<?php echo $data['apellido_paterno_postulante'] ?? ''; ?>" required></td></tr>
                    <tr><th>Apellido Materno:</th><td><input type="text" name="apellido_materno_postulante" value="<?php echo $data['apellido_materno_postulante'] ?? ''; ?>" required></td></tr>
                    <tr><th>Celular:</th><td><input type="tel" name="celular_postulante" value="<?php echo $data['celular_postulante'] ?? ''; ?>" maxlength="9" required></td></tr>
                    <tr><th>Dirección:</th><td><input type="text" name="direccion_postulante" value="<?php echo $data['direccion_postulante'] ?? ''; ?>" required></td></tr>
                    <tr><th>Fecha de Nacimiento:</th><td><input type="date" name="fecha_nacimiento_postulante" value="<?php echo $data['fecha_nacimiento_postulante'] ?? ''; ?>" required></td></tr>
                </table>
            <?php elseif ($tipo_usuario == 2): ?>
                <!-- Formulario para Empresa -->
                <table border="1" cellpadding="10">
                    <tr><th>RUC:</th><td><input type="text" name="ruc_empresa" value="<?php echo $data['ruc_empresa'] ?? ''; ?>" maxlength="11" required></td></tr>
                    <tr><th>Razón Social:</th><td><input type="text" name="razon_social_empresa" value="<?php echo $data['razon_social_empresa'] ?? ''; ?>" required></td></tr>
                    <tr><th>Celular:</th><td><input type="tel" name="celular_empresa" value="<?php echo $data['celular_empresa'] ?? ''; ?>" maxlength="9" required></td></tr>
                    <tr><th>Dirección:</th><td><input type="text" name="direccion_empresa" value="<?php echo $data['direccion_empresa'] ?? ''; ?>" required></td></tr>
                    <tr><th>Representante:</th><td><input type="text" name="representante_empresa" value="<?php echo $data['representante_empresa'] ?? ''; ?>" required></td></tr>
                </table>
            <?php endif; ?>
            <br>
            <button type="submit" name="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>