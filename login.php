<?php
session_start();
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesi¨®n</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

die("Header cargado correctamente.");
?>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Iniciar Sesion</h1>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="process_login.php" method="post">
            <label for="username" class="block text-gray-700 dark:text-gray-300">Usuario:</label>
            <input type="text" name="username" id="username" required class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700 mb-4" />

            <label for="password" class="block text-gray-700 dark:text-gray-300">Contrasena:</label>
            <input type="password" name="password" id="password" required class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700 mb-4" />

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">Iniciar Sesion</button>
        </form>
    </div>
</body>
</html>
