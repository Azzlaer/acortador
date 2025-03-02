<?php
session_start();
require 'config.php';
header("Content-Type: text/html; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Consulta para obtener las estadísticas
$query = "
    SELECT 
        urls.id, 
        urls.short_code, 
        urls.original_url, 
        IFNULL(url_statistics.total_clicks, 0) AS total_clicks, 
        IFNULL(url_statistics.last_clicked, 'Nunca') AS last_clicked
    FROM urls
    LEFT JOIN url_statistics ON urls.id = url_statistics.url_id
    WHERE urls.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$statistics = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calcular el total de clics
$total_clicks = array_sum(array_column($statistics, 'total_clicks'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <?php include 'header.php'; ?>

    <!-- Contenido principal -->
    <div class="flex justify-center mt-6">
        <div class="w-full max-w-4xl bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Estadísticas de tus URLs</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Consulta los detalles de las visitas a tus URLs acortadas.</p>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Cantidad de clicks totales: <?php echo $total_clicks; ?></h2>
            
            <!-- Tabla de estadísticas -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">URL Acortada</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">URL Original</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Clics Totales</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Último Clic</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statistics as $index => $stat): ?>
                            <tr class="<?php echo $index % 2 == 0 ? 'bg-gray-100 dark:bg-gray-800' : 'bg-white dark:bg-gray-700'; ?> border border-gray-300 dark:border-gray-600">
                                <td class="px-4 py-2">
                                    <a href="https://www.latinbattle.com/url/<?php echo htmlspecialchars($stat['short_code']); ?>" target="_blank" class="text-blue-500 hover:underline">
                                        <?php echo htmlspecialchars($stat['short_code']); ?>
                                    </a>
                                </td>
                                <td class="px-4 py-2 truncate max-w-xs overflow-hidden"> <?php echo htmlspecialchars($stat['original_url']); ?> </td>
                                <td class="px-4 py-2 text-center"> <?php echo $stat['total_clicks']; ?> </td>
                                <td class="px-4 py-2 text-center"> <?php echo $stat['last_clicked']; ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
