<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Verificar si el nombre de usuario ya está en uso
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "El nombre de usuario ya está en uso.";
    } else {
        // Insertar nuevo usuario
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Error al registrar el usuario.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Registro de Usuario</h1>

        <?php if (isset($error)): ?>
            <p class="text-red-500 mt-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" class="mt-6 space-y-6">
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Nombre de Usuario</label>
                <input type="text" name="username" required class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Contraseña</label>
                <input type="password" name="password" required class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold p-3 rounded-xl transition-all">Registrarse</button>
        </form>

        <p class="mt-4 text-gray-600 dark:text-gray-400">¿Ya tienes una cuenta? <a href="login.php" class="text-blue-500 hover:underline">Iniciar sesión</a></p>
    </div>
</body>
</html>
