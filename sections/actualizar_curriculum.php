<?php
include __DIR__ . "/conexion.php";

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener los datos actuales del usuario
$sql_datos = "
    SELECT c.perfil_postulante_curriculum, c.id_carrera, c.ruta_curriculum
    FROM postulante p
    INNER JOIN curriculum c ON p.id_curriculum = c.id_curriculum
    WHERE p.id_usuario = ?";
$stmt = mysqli_prepare($cn, $sql_datos);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$datos = mysqli_fetch_assoc($result);
$perfil_actual = $datos['perfil_postulante_curriculum'] ?? '';
$id_carrera_actual = $datos['id_carrera'] ?? '';
$ruta_curriculum_actual = $datos['ruta_curriculum'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curriculum</title>
    <link rel="stylesheet" href="../css/curriculum.css">
</head>

<body>
    <center>
        <h3>Editar su Curriculum</h3>
        <h1>EDITAR CURRICULUM</h1>

        <!-- Mostrar mensajes de error -->
        <?php
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'solo_pdf':
                    echo '<div style="color:red; text-align:center;">¡Error! Solo se permiten archivos PDF.</div>';
                    break;
                case 'archivo_vacio':
                    echo '<div style="color:red; text-align:center;">¡Error! El archivo está vacío.</div>';
                    break;
                case 'upload':
                    echo '<div style="color:red; text-align:center;">¡Error! No se pudo subir el archivo.</div>';
                    break;
                case 'database':
                    echo '<div style="color:red; text-align:center;">¡Error! No se pudo guardar en la base de datos.</div>';
                    break;
                case 'no_archivo':
                    echo '<div style="color:red; text-align:center;">¡Error! No se ha seleccionado ningún archivo.</div>';
                    break;
            }
        }
        ?>
    </center>
    <br>

    <form action="../control/p_actualizar_curriculum.php" method="post" enctype="multipart/form-data">

        <fieldset align="center">

            <table border="0" align="center">
                <tr>
                    <td align="right">
                        <strong>Carrera / Cargo: </strong>
                    </td>
                    <td align="left">
                        <select name="txtcargo">

                            <?php
                            $sql = "SELECT * FROM carrera";
                            $fila = mysqli_query($cn, $sql);
                            while ($r = mysqli_fetch_assoc($fila)) {
                                $selected = ($r['id_carrera'] == $id_carrera_actual) ? 'selected' : '';
                                ?>
                                <option value="<?php echo $r['id_carrera']; ?>" <?php echo $selected; ?>>
                                    <?php echo $r['nombre_carrera']; ?>
                                </option>
                                <?php
                            }
                            ?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="right"><strong>Perfil Postulante: </strong></td>
                    <td align="left">
                        <textarea name="txtperfil" rows="8" cols="50"><?php echo htmlspecialchars($perfil_actual); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="right"><strong>Subir Curriculum (PDF) :</strong></td>
                    <td align="left">
                        <?php if ($ruta_curriculum_actual): ?>
                            <p>Archivo actual: <a href="../curriculum/<?php echo $ruta_curriculum_actual; ?>" target="_blank">
                                <?php echo $ruta_curriculum_actual; ?>
                            </a></p>
                        <?php endif; ?>
                        <br><input type="file" name="archivo" id="">
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="" align="center">
                        <br><input type="submit" value="Actualizar Curriculum">
                    </td>
                </tr>

            </table>
        </fieldset>
    </form>

</body>

</html>
