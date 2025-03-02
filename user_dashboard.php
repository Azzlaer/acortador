<?php
session_start();
require 'config.php';
header("Content-Type: text/html; charset=UTF-8");


// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Consultar todas las URLs del usuario logueado junto con el número de clics
$query = "
    SELECT urls.*, 
           COALESCE(COUNT(url_clicks.id), 0) AS clicks 
    FROM urls
    LEFT JOIN url_clicks ON urls.id = url_clicks.url_id
    WHERE urls.user_id = ?
    GROUP BY urls.id
    ORDER BY urls.created_at DESC";  // Ordenamos por fecha descendente


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$urls = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    
    
   <?php include 'header.php'; ?>

    <!-- Contenido principal -->
    <div class="flex justify-center mt-6">
        <div class="w-full max-w-3xl bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Panel de Usuario</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Bienvenido, aqui puedes gestionar tus URLs acortadas</p>

            <!-- Formulario para agregar nuevas URLs -->
            <div class="relative w-full mb-6">
                <input id="urlInput" type="text" placeholder="Ingresa tu URL aqui..." class="w-full p-3 text-gray-700 dark:text-white bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button id="shortenBtn" class="mt-3 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold p-3 rounded-xl transition-all">Acortar</button>
            </div>

            <!-- Sección de historial de URLs -->
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mt-6">Historial de URLs</h2>
            <ul id="history" class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                <?php foreach ($urls as $url): ?>
                    <li>
						<a href="https://www.latinbattle.com/url/<?php echo htmlspecialchars($url['short_code']); ?>" target="_blank" class="text-blue-500 hover:underline">
						https://www.latinbattle.com/url/<?php echo htmlspecialchars($url['short_code']); ?>
						</a>

                        - Clics: <?php echo $url['clicks']; ?> - Fecha: <?php echo $url['created_at']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        document.getElementById("shortenBtn").addEventListener("click", function() {
            let input = document.getElementById("urlInput").value;
            if (input.trim() === "") {
                alert("Por favor, ingresa una URL valida.");
                return;
            }

            // Enviar la URL al servidor para generar la URL corta
            fetch('store_url.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `url=${encodeURIComponent(input)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("URL acortada con exito. Recarga la pagina para ver la nueva entrada en el historial.");
                    location.reload();
                } else {
                    alert("Error al acortar la URL. Por favor, intenta nuevamente.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Ocurrio un error al conectar con el servidor.");
            });
        });
    </script>
</body>
</html>