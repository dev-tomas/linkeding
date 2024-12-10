<?php
require_once __DIR__.'/../control/p_home.php';
include("conexion.php");

// Verificar si el usuario está autenticado y tiene el rol de postulante
if (isset($_SESSION['usuario_id'])) {
    $id_usuario_logueado = $_SESSION['usuario_id'];

    $sql_postulante = "SELECT id_postulante FROM postulante WHERE id_usuario = ?";
    $stmt_postulante = mysqli_prepare($cn, $sql_postulante);
    mysqli_stmt_bind_param($stmt_postulante, "i", $id_usuario_logueado);
    mysqli_stmt_execute($stmt_postulante);
    $resultado_postulante = mysqli_stmt_get_result($stmt_postulante);
    $postulante = mysqli_fetch_assoc($resultado_postulante);
    $id_postulante = $postulante['id_postulante'];

    // Paginación
    $itemsPorPagina = 5;
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($paginaActual - 1) * $itemsPorPagina;

    // Contar total de postulaciones
    $sqlTotal = "SELECT COUNT(*) AS total FROM detalle_postulante_propuesta WHERE id_postulante = ?";
    $stmtTotal = $cn->prepare($sqlTotal);
    $stmtTotal->bind_param("i", $id_postulante);
    $stmtTotal->execute();
    $resultadoTotal = $stmtTotal->get_result();
    $totalPostulaciones = $resultadoTotal->fetch_assoc()['total'];
    $stmtTotal->close();

    $totalPaginas = ceil($totalPostulaciones / $itemsPorPagina);

    // Consultar postulaciones con límite y desplazamiento
    $sql = "SELECT p.nombre_propuesta, dp.fecha_postulacion
            FROM detalle_postulante_propuesta dp
            INNER JOIN propuesta p ON dp.id_propuesta = p.id_propuesta
            WHERE dp.id_postulante = ?
            LIMIT ? OFFSET ?";
    $fila = $cn->prepare($sql);
    $fila->bind_param("iii", $id_postulante, $itemsPorPagina, $offset);
    $fila->execute();
    $resultado = $fila->get_result();
} else {
    echo "Acceso no permitido. Debes iniciar sesión.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Postulaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/postulaciones.css">
</head>
<body>
    <Center>
        <h2>MIS POSTULACIONES</h2>
    </Center>

    <div class="contenido">
    <table class="offer-table">
        <thead>
            <tr>
                <th class="header-column"><strong>Nombre de la Propuesta</strong></th>
                <th class="header-column"><strong>Fecha de Postulación</strong></th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verificar si hay resultados
            if ($resultado->num_rows > 0) {
                while ($r = $resultado->fetch_assoc()) {
                    echo "<tr>
                            <td data-label='Nombre de la Propuesta'>" . htmlspecialchars($r['nombre_propuesta']) . "</td>
                            <td data-label='Fecha de Postulación'>" . htmlspecialchars($r['fecha_postulacion']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2' class='no-data'>No tienes postulaciones registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>
    <div class="paginacion">
    <nav>
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?= ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=mis_postulaciones&pagina=<?= $paginaActual - 1; ?>" <?= ($paginaActual <= 1) ? 'tabindex="-1"' : ''; ?>>Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i == $paginaActual ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?page=mis_postulaciones&pagina=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=mis_postulaciones&pagina=<?= $paginaActual + 1; ?>" <?= ($paginaActual >= $totalPaginas) ? 'tabindex="-1"' : ''; ?>>Siguiente</a>
                </li>
            </ul>
        </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>