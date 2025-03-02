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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gana Dinero con Nuestro Acortador</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <?php include 'header.php'; ?>

    <div class="flex justify-center mt-6">
        <div class="w-full max-w-3xl bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Gana Dinero con Nuestro Acortador</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Ahora puedes ganar dinero simplemente compartiendo enlaces acortados. Cuantos más clics generes en tus enlaces, más dinero ganarás.
            </p>
            
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">¿Cómo funciona?</h2>
            <ul class="list-disc pl-6 text-gray-600 dark:text-gray-300 mb-4">
                <li>Crea y comparte enlaces acortados con nuestro servicio.</li>
                <li>Cada clic en tus enlaces se acumula en tu cuenta.</li>
                <li>Cuando alcances un número mínimo de clics, podrás solicitar tu pago.</li>
                <li>Recibe tu dinero mediante transferencia bancaria, Paypal, Binance o World Coin.</li>
            </ul>
            
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Tarifas de Pago</h2>
            <ul class="list-disc pl-6 text-gray-600 dark:text-gray-300 mb-4">
                <li>1,000 clics = $X</li>
                <li>10,000 clics = $Y</li>
                <li>100,000 clics = $Z</li>
            </ul>

            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Solicita tu Pago</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Una vez que alcances el mínimo requerido, podrás solicitar tu pago desde tu panel de usuario.</p>
            
            <a href="user_referidos.php" class="block text-center bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">¡También puedes ganar teniendo referidos!</a>
        </div>
    </div>
</body>
</html>
