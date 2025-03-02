<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Consultar el total de clics
$query = "SELECT COUNT(url_clicks.id) AS total_clicks FROM url_clicks INNER JOIN urls ON url_clicks.url_id = urls.id WHERE urls.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_clicks = $result->fetch_assoc()['total_clicks'];
$stmt->close();

// Consultar los clics por fecha (últimos 7 días)
$query = "SELECT DATE(url_clicks.click_date) AS click_date, COUNT(url_clicks.id) AS clicks 
          FROM url_clicks 
          INNER JOIN urls ON url_clicks.url_id = urls.id 
          WHERE urls.user_id = ? AND url_clicks.click_date >= DATE(NOW()) - INTERVAL 7 DAY 
          GROUP BY click_date ORDER BY click_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$clicks_by_date = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Convertir resultados a formato JSON para usar con Chart.js
$dates = array_column($clicks_by_date, 'click_date');
$clicks = array_column($clicks_by_date, 'clicks');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Estadísticas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
<?php include 'header.php'; ?>
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-white">Panel de Estadísticas</h1>
    <p class="text-center text-gray-600 dark:text-gray-400">Aquí puedes ver el resumen de tu actividad.</p>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg mt-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Total de clics</h2>
        <p class="text-2xl font-bold text-blue-500"><?php echo $total_clicks; ?></p>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg mt-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Clics de los últimos 7 días</h2>
        <canvas id="clickChart"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('clickChart').getContext('2d');
    const clickChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Clics',
                data: <?php echo json_encode($clicks); ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2
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
