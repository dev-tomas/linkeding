<?php
include __DIR__ . '/../sections/conexion.php';
include __DIR__ . '/p_obtener_curriculum.php';


$ruta_curriculum = obtener_direccion_curriculum($_SESSION['usuario_id']);

if (empty($ruta_curriculum)) {
    echo "<script>
                alert('Tiene que subir su curriculum para ver ofertas');
                window.location.href = '../index.php?page=subir_curriculum';
                </script>";
    exit();

} else {

    $postulante = $_SESSION['usuario_id'];

    // PAGINACIÓN
    $NumeroNotificaciones = 5;
    $PaginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $Orden = ($PaginaActual - 1) * $NumeroNotificaciones;

    $sql = "SELECT 
    e.id_empresa,
    e.razon_social_empresa AS nombre_empresa, 
    p.nombre_propuesta AS propuesta,
    ep.nombre_estado_propuesta
    FROM 
        detalle_empresa_propuesta dep
    INNER JOIN 
        empresa e ON dep.id_empresa = e.id_empresa
    INNER JOIN 
        propuesta p ON dep.id_propuesta = p.id_propuesta
    INNER JOIN 
        estado_propuesta ep ON p.id_estado_propuesta = ep.id_estado_propuesta
    WHERE
        ep.nombre_estado_propuesta IN ('activo','expirado')
    LIMIT $Orden, $NumeroNotificaciones;";
;


        $fila = mysqli_query($cn, $sql);

        if (!$fila) {
            die("Error en la consulta SQL: " . mysqli_error($cn));
        }

        $sql_total = "SELECT COUNT(*) AS total 
        FROM detalle_empresa_propuesta dep
        INNER JOIN propuesta p ON dep.id_propuesta = p.id_propuesta
        INNER JOIN estado_propuesta ep ON p.id_estado_propuesta = ep.id_estado_propuesta
        WHERE ep.nombre_estado_propuesta IN ('activo', 'expirado');
        ";
        $resultado_total = mysqli_query($cn, $sql_total);
        $total_registros = mysqli_fetch_assoc($resultado_total)['total'];//numero total


        $TotalPaginas = ceil($total_registros / $NumeroNotificaciones);
}
;
?>