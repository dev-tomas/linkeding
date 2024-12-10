<?php
include("conexion.php");

//GRAFICA 1 TOTAL DE USUARIOS
// Consulta para contar postulantes
$sql_postulantes = "SELECT COUNT(*) AS total_postulantes FROM postulante";
$result_postulantes = mysqli_query($cn, $sql_postulantes);
$data_postulantes = mysqli_fetch_assoc($result_postulantes);

// Consulta para contar empresas
$sql_empresas = "SELECT COUNT(*) AS total_empresas FROM empresa";
$result_empresas = mysqli_query($cn, $sql_empresas);
$data_empresas = mysqli_fetch_assoc($result_empresas);



//GRAFICO 2
//--> Consulta para contar propuestas activas
$sql_propuestas_a = "SELECT COUNT(*) AS total_propuestas_activas FROM propuesta WHERE id_estado_propuesta = 1";
$result_propuestas_a = mysqli_query($cn, $sql_propuestas_a);
$data_propuestas_a = mysqli_fetch_assoc($result_propuestas_a);

//--> Consulta para contar propuestas inactivas
$sql_propuestas_ina = "SELECT COUNT(*) AS total_propuestas_inactivas FROM propuesta WHERE id_estado_propuesta = 2";
$result_propuestas_ina = mysqli_query($cn, $sql_propuestas_ina);
$data_propuestas_ina = mysqli_fetch_assoc($result_propuestas_ina);


//GRAFICO 3
// POSTULANTE 
//Postulantes estado
//Activo
$sql_estado_postulante_a = "SELECT COUNT(*) AS total_estado_a FROM postulante WHERE id_estado_postulante = 1";
$result_estado_postulante_a = mysqli_query($cn, $sql_estado_postulante_a);
$data_estado_a = mysqli_fetch_assoc($result_estado_postulante_a);
//Inactivo
$sql_estado_postulante_i = "SELECT COUNT(*) AS total_estado_ina FROM postulante WHERE id_estado_postulante = 2";
$result_estado_postulante_i = mysqli_query($cn, $sql_estado_postulante_i);
$data_estado_i = mysqli_fetch_assoc($result_estado_postulante_i);
//Suspendido
$sql_estado_postulante_sus = "SELECT COUNT(*) AS total_estado_sus FROM postulante WHERE id_estado_postulante = 3";
$result_estado_postulante_sus = mysqli_query($cn, $sql_estado_postulante_sus);
$data_estado_sus = mysqli_fetch_assoc($result_estado_postulante_sus);
//Eliminado
$sql_estado_postulante_eli = "SELECT COUNT(*) AS total_estado_elim FROM postulante WHERE id_estado_postulante = 4";
$result_estado_postulante_eli = mysqli_query($cn, $sql_estado_postulante_eli);
$data_estado_eli = mysqli_fetch_assoc($result_estado_postulante_eli);


//GRAFICA 4
//ESTADO EMPRESA
$sql_estado_empresa_a = "SELECT COUNT(*) AS empresa_estado_a FROM empresa WHERE id_estado_empresa = 1";
$result_estado_emrpesa_a = mysqli_query($cn, $sql_estado_empresa_a);
$data_empresa_a = mysqli_fetch_assoc($result_estado_emrpesa_a);

$sql_estado_empresa_ina = "SELECT COUNT(*) AS empresa_estado_ina FROM empresa WHERE id_estado_empresa = 2";
$result_estado_emrpesa_ina = mysqli_query($cn, $sql_estado_empresa_ina);
$data_empresa_ina = mysqli_fetch_assoc($result_estado_emrpesa_ina);



// Total de usuarios
$sql_usuarios = "SELECT COUNT(*) AS total_usuarios FROM usuario";
$result_usuarios = mysqli_query($cn, $sql_usuarios);
$data_usuarios = mysqli_fetch_assoc($result_usuarios);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control - Linkeding</title>
    <link rel="stylesheet" href="../css/estadistica.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="analytics-dashboard">
        <div class="dashboard-header">
            <h1>Panel de control - Linkeding</h1>
            <p>Información detallada sobre usuarios, empresas y propuestas</p>
        </div>

        <div class="summary-card">
            <div class="summary-grid">
                <div class="summary-item blue-metric">
                    <div class="summary-number"><?php echo $data_postulantes['total_postulantes']; ?></div>
                    <div class="summary-label">Postulantes</div>
                </div>
                <div class="summary-item green-metric">
                    <div class="summary-number"><?php echo $data_empresas['total_empresas']; ?></div>
                    <div class="summary-label">Empresas</div>
                </div>
                <div class="summary-item purple-metric">
                    <div class="summary-number"><?php echo $data_propuestas_a['total_propuestas_activas']; ?></div>
                    <div class="summary-label">Propuestas Activas</div>
                </div>
                <div class="summary-item red-metric">
                    <div class="summary-number"><?php echo $data_usuarios['total_usuarios']; ?></div>
                    <div class="summary-label">Total Usuarios</div>
                </div>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-card">
                <h3>Postulantes vs Empresas</h3>
                <canvas id="generalChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Estado Propuestas</h3>
                <canvas id="propuestaChart"></canvas>
            </div>
            <div class="chart-card" style="grid-column: span 2;">
                <h3>Estado de Postulantes</h3>
                <canvas id="estadoPostulantesChart"></canvas>
            </div>
        </div>

        <div class="dashboard-footer">
            © 2024 Panel de control de Linkeding
        </div>
    </div>
    <script>
        // Mantén los scripts de Chart.js originales
        // GRAFICO 1
        new Chart(document.getElementById('generalChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Postulantes', 'Empresas'],
                datasets: [{
                    data: [<?php echo $data_postulantes['total_postulantes']; ?>, <?php echo $data_empresas['total_empresas']; ?>],
                    backgroundColor: ['rgba(75, 192, 192, 0.8)', 'rgba(54, 162, 235, 0.8)']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    
        // GRAFICO 2
        new Chart(document.getElementById('propuestaChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Propuestas Activas', 'Propuestas Inactivas'],
                datasets: [{
                    data: [<?php echo $data_propuestas_a['total_propuestas_activas']; ?>, <?php echo $data_propuestas_ina['total_propuestas_inactivas']; ?>],
                    backgroundColor: ['rgba(54, 162, 235, 0.8)', 'rgba(255, 99, 132, 0.8)']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // GRAFICO 3
        new Chart(document.getElementById('estadoPostulantesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Activo', 'Inactivo', 'Suspendido', 'Eliminado'],
                datasets: [{
                    label: 'Estado de Postulantes',
                    data: [
                        <?php echo $data_estado_a['total_estado_a']; ?>,
                        <?php echo $data_estado_i['total_estado_ina']; ?>,
                        <?php echo $data_estado_sus['total_estado_sus']; ?>,
                        <?php echo $data_estado_eli['total_estado_elim']; ?>
                    ],
                    backgroundColor: ['rgba(54, 162, 235, 0.8)', 'rgba(255, 159, 64, 0.8)', 'rgba(255, 205, 86, 0.8)', 'rgba(75, 192, 192, 0.8)']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>