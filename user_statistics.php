<?php
session_start();
require 'config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener estadísticas generales de las URLs del usuario
$stmt = $conn->prepare("SELECT COUNT(*) AS total_url, SUM(clicks) AS total_clicks FROM url WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();
$stmt->close();

// Obtener clics por día
$stmt = $conn->prepare("SELECT DATE(created_at) AS date, COUNT(*) AS total FROM url WHERE user_id = ? GROUP BY DATE(created_at)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$clicks_per_day = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <!-- Menú de navegación -->
    <nav class="bg-gray-800 p-4 text-white flex justify-between items-center">
        <div class="text-lg font-semibold">Acortador de URLs</div>
        <div class="space-x-4">
            <a href="user_dashboard.php" class="hover:underline">Mis URLs</a>
            <a href="user_statistics.php" class="hover:underline">Estadísticas</a>
            <a href="user_settings.php" class="hover:underline">Configuración</a>
            <a href="logout.php" class="hover:underline">Cerrar sesión</a>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="flex justify-center mt-6">
        <div class="w-full max-w-3xl bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Estadísticas de Usuario</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Aquí puedes ver estadísticas detalladas de tus URLs acortadas.</p>

            <!-- Mostrar estadísticas generales -->
            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-xl mb-6">
                <p class="text-lg font-semibold">Total de URLs acortadas: <?php echo $stats['total_urls']; ?></p>
                <p class="text-lg font-semibold">Total de clics: <?php echo $stats['total_clicks']; ?></p>
            </div>

            <!-- Mostrar clics por día -->
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mt-6">Clics por día</h2>
            <ul class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                <?php if (count($clicks_per_day) > 0): ?>
                    <?php foreach ($clicks_per_day as $day): ?>
                        <li>Fecha: <?php echo $day['date']; ?> - Clics: <?php echo $day['total']; ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No hay clics registrados.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>
