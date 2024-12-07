<?php
require_once __DIR__ . '/../sections/conexion.php';

function obtener_direccion_curriculum($id_usuario) {
    global $cn;

    $sql = "SELECT c.ruta_curriculum
            FROM curriculum c
            INNER JOIN postulante p ON c.id_curriculum = p.id_curriculum
            WHERE p.id_usuario = ?";

    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) return 'subir_curriculum.php';

    mysqli_stmt_bind_param($stmt, "i", $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return 'subir_curriculum.php';
    }

    $resultado = mysqli_stmt_get_result($stmt);
    if (!$resultado) {
        mysqli_stmt_close($stmt);
        return 'subir_curriculum.php';
    }

    $fila = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    return (!empty($fila['ruta_curriculum'])) 
        ? '../curriculum/' . $fila['ruta_curriculum'] 
        : '0';
}

function obtener_nombre_carrera($id_usuario) {
    global $cn;

    $sql = "SELECT ca.nombre_carrera
            FROM carrera ca
            INNER JOIN curriculum c ON ca.id_carrera = c.id_carrera
            INNER JOIN postulante p ON c.id_curriculum = p.id_curriculum
            WHERE p.id_usuario = ?";

    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) return null;

    mysqli_stmt_bind_param($stmt, "i", $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return null;
    }

    $resultado = mysqli_stmt_get_result($stmt);
    if (!$resultado) {
        mysqli_stmt_close($stmt);
        return null;
    }

    $fila = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    return $fila['nombre_carrera'] ?? 'No ha subido curriculum';
}

function obtener_perfil_postulante_curriculum($id_usuario) {
    global $cn;

    $sql = "SELECT c.perfil_postulante_curriculum
            FROM curriculum c
            INNER JOIN postulante p ON c.id_curriculum = p.id_curriculum
            WHERE p.id_usuario = ?";

    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) return null;

    mysqli_stmt_bind_param($stmt, "i", $id_usuario);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return null;
    }

    $resultado = mysqli_stmt_get_result($stmt);
    if (!$resultado) {
        mysqli_stmt_close($stmt);
        return null;
    }

    $fila = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    return $fila['perfil_postulante_curriculum'] ?? 'No ha subido Curriculum';
}
?>
