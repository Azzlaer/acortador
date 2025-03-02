<?php
session_start();
require 'config.php';
require 'GoogleAuthenticator.php';

// Obtener el código de referido desde la URL si está presente
$referral_code = isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : '';

// Manejar el registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $referred_by = isset($_POST['referral_code']) ? trim($_POST['referral_code']) : null;

    // Verificar si el usuario o el correo ya existen
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "El nombre de usuario o el correo electrónico ya están registrados.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, referred_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $referred_by);

        if ($stmt->execute()) {
            $success = "Usuario registrado con éxito. Por favor, inicia sesión.";
        } else {
            $error = "Hubo un problema al registrar al usuario.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Registro de Usuario</h1>

        <?php if (isset($success)): ?>
            <p class="text-green-500 mb-4"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 dark:text-gray-300">Nombre de Usuario</label>
                <input type="text" id="username" name="username" required class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label for="email" class="block text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                <input type="email" id="email" name="email" required class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label for="password" class="block text-gray-700 dark:text-gray-300">Contraseña</label>
                <input type="password" id="password" name="password" required class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label for="referral_code" class="block text-gray-700 dark:text-gray-300">Código de Referido (Opcional)</label>
                <input type="text" id="referral_code" name="referral_code" value="<?php echo $referral_code; ?>" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold p-3 rounded-xl transition-all">Registrarse</button>
        </form>

        <p class="text-gray-600 dark:text-gray-400 text-sm mt-4">¿Ya tienes una cuenta? <a href="login.php" class="text-blue-500 hover:underline">Inicia sesión</a></p>
    </div>
</body>
</html>
