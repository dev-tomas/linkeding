<?php
include("conexion.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Curriculum</title>
    <link rel="stylesheet" href="../css/curriculum.css">
</head>
<body>
    <center>
        <h1>SUBIR CURRICULUM</h1>
        
        <!-- Mostrar mensajes de error -->
        <?php
        if(isset($_GET['error'])) {
            switch($_GET['error']) {
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

    <form action="/linkeding/control/p_curriculum.php" method="post" enctype="multipart/form-data">

        <fieldset align="center">

            <table border="0" align="center">
                <tr>
                    <td align="right">
                        <strong>Carrera / Cargo: </strong>
                    </td>
                    <td align="left">
                        <select name="txtcargo">

                            <?php

                            $sql = "select * from carrera";
                            $fila = mysqli_query($cn, $sql);
                            while ($r = mysqli_fetch_assoc($fila)) {



                                ?>

                                <option value="<?php echo $r["id_carrera"] ?>"><?php echo $r["nombre_carrera"]; ?></option>

                            <?php

                            }

                            ?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="right"><strong>Perfil Postulante: </strong></td>
                    <td align="left">
                        <textarea name="txtperfil" rows="8" cols="50"></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="right"><strong>Subir Curriculum (PDF) :</strong></td>
                    <td align="left">
                        <br><input type="file" name="archivo" id="">
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="" align="center">
                        <br><input type="submit" value="Publicar Curriculum">
                    </td>
                </tr>

            </table>
        </fieldset>
    </form>

</body>
</html>