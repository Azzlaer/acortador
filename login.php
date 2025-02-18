<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Almacenar la sesión
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header('Location: user_dashboard.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Inicio de Sesion</h1>
        <form action="process_login.php" method="post">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 dark:text-gray-300">Nombre de Usuario</label>
                <input type="text" name="username" id="username" required class="w-full p-3 text-gray-700 dark:text-white bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 dark:text-gray-300">Contrasena</label>
                <input type="password" name="password" id="password" required class="w-full p-3 text-gray-700 dark:text-white bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold p-3 rounded-xl transition-all">Iniciar Sesion</button>
        </form>
        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">Quiero una cuenta <a href="register.php" class="text-blue-500 hover:underline">Registrate aqui</a></p>
    </div>
</body>
</html>
