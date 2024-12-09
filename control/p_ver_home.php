<?php
include __DIR__ . '/p_obtener_datos_empresa.php';
include __DIR__ . '/p_obtener_datos_postulante.php';
require_once __DIR__ . '/../control/p_obtener_curriculum.php';

// Primero, verifica si se ha pasado un ID de empresa o postulante por GET
$id_empresa_visitada = isset($_GET['id_empresa']) ? intval($_GET['id_empresa']) : null;
$id_postulante_visitado = isset($_GET['id_postulante']) ? intval($_GET['id_postulante']) : null;

// Variables para almacenar los datos
$ruta_imagen_portada = '../img/default-cover.jpg';
$ruta_imagen_usuario = '../img/user.svg';
$nombre = '';
$id_rol = null;

// Verificar si se está visitando una empresa
if ($id_empresa_visitada) {
    $empresa_visitada = obtenerDatosEmpresa($id_empresa_visitada);

    if ($empresa_visitada) {
        $ruta_imagen_portada = $empresa_visitada['ruta_imagen_portada'] ? '../img/portada/' . $empresa_visitada['ruta_imagen_portada'] : '../img/default-cover.jpg';
        $ruta_imagen_usuario = $empresa_visitada['ruta_imagen_usuario'] ? '../img/usuario/' . $empresa_visitada['ruta_imagen_usuario'] : '../img/user.svg';
        $nombre = $empresa_visitada['razon_social_empresa'];
        $ruc = $empresa_visitada['ruc_empresa'];
        $representante = $empresa_visitada['representante_empresa'];
        $direccion = $empresa_visitada['direccion_empresa'];
        $celular = $empresa_visitada['celular_empresa'];
        $id_rol = 2; // Rol de empresa
    }
} 
// Verificar si se está visitando un postulante
else if ($id_postulante_visitado) {
    $postulante_visitado = obtenerDatosPostulante($id_postulante_visitado);

    if ($postulante_visitado) {
        $ruta_imagen_portada = $postulante_visitado['ruta_imagen_portada'] ? '../img/portada/' . $postulante_visitado['ruta_imagen_portada'] : '../img/default-cover.jpg';
        $ruta_imagen_usuario = $postulante_visitado['ruta_imagen_usuario'] ? '../img/usuario/' . $postulante_visitado['ruta_imagen_usuario'] : '../img/user.svg';
        $nombre = $postulante_visitado['nombre_postulante'] . ' ' . $postulante_visitado['apellido_paterno_postulante'] . ' ' . $postulante_visitado['apellido_materno_postulante'];
        $dni = $postulante_visitado['dni_postulante'];
        $cip = $postulante_visitado['cip_postulante'];
        $curriculum = $postulante_visitado['id_curriculum'];
        $direccion = $postulante_visitado['direccion_postulante'];
        $celular = $postulante_visitado['celular_postulante'];
        $id_rol = 3; // Rol de postulante
    }
}

// Si no se encontraron datos
if (!$id_rol) {
    die("Datos no encontrados");
}
?>