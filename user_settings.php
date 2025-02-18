<?php
session_start();
require 'config.php';
require 'GoogleAuthenticator.php'; // Incluir la librería para 2FA

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$ga = new PHPGangsta_GoogleAuthenticator(); // Inicializar Google Authenticator

// Manejar actualizaciones de configuración
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cambiar contraseña
    if (isset($_POST['current_password'], $_POST['new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        // Verificar la contraseña actual
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($stored_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($current_password, $stored_password)) {
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password, $user_id);
            $stmt->execute();
            $stmt->close();
            $message = "Contraseña actualizada correctamente.";
        } else {
            $error = "La contraseña actual es incorrecta.";
        }
    }

    // Actualizar correo electrónico, 2FA y datos personales
    $email = $_POST['email'] ?? '';
    $two_fa = isset($_POST['two_fa']) ? 1 : 0;
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $birth_date = $_POST['birth_date'] ?? '';

    // Generar y almacenar el secreto de 2FA si se activa por primera vez
    if ($two_fa && empty($_SESSION['2fa_secret'])) {
        $secret = $ga->createSecret();
        $_SESSION['2fa_secret'] = $secret; // Almacenar temporalmente
        $qrCodeUrl = $ga->getQRCodeGoogleUrl('Acortador de URLs', $secret);
    } else {
        $secret = $_SESSION['2fa_secret'] ?? '';
    }

    $stmt = $conn->prepare("UPDATE users SET email = ?, two_fa = ?, first_name = ?, last_name = ?, birth_date = ?, two_fa_secret = ? WHERE id = ?");
    $stmt->bind_param("sissssi", $email, $two_fa, $first_name, $last_name, $birth_date, $secret, $user_id);
    $stmt->execute();
    $stmt->close();

    $message = $message ?? "Configuración actualizada correctamente.";
}

// Obtener la configuración actual del usuario
$stmt = $conn->prepare("SELECT email, two_fa, first_name, last_name, birth_date, two_fa_secret FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($email, $two_fa, $first_name, $last_name, $birth_date, $two_fa_secret);
$stmt->fetch();
$stmt->close();

$qrCodeUrl = $two_fa_secret ? $ga->getQRCodeGoogleUrl('Acortador de URLs', $two_fa_secret) : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Usuario</title>
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

    <div class="flex justify-center mt-6">
        <div class="w-full max-w-3xl bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Configuración de Usuario</h1>

            <?php if (isset($message)): ?>
                <p class="text-green-500 mt-4"><?php echo $message; ?></p>
            <?php elseif (isset($error)): ?>
                <p class="text-red-500 mt-4"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- Formulario de configuración -->
            <form method="POST" class="mt-6 space-y-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Cambiar Contraseña</h2>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Contraseña Actual</label>
                    <input type="password" name="current_password" required class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Nueva Contraseña</label>
                    <input type="password" name="new_password" required class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                </div>

                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Información Personal</h2>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Apellido</label>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Fecha de Nacimiento</label>
                    <input type="date" name="birth_date" value="<?php echo htmlspecialchars($birth_date); ?>" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                </div>

                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Autenticación 2FA</h2>
                <div class="flex items-center">
                    <input type="checkbox" name="two_fa" <?php echo $two_fa ? 'checked' : ''; ?> class="mr-2">
                    <label class="text-gray-700 dark:text-gray-300">Activar Autenticación en Dos Factores (2FA)</label>
                </div>

                <?php if ($two_fa && $qrCodeUrl): ?>
                    <div class="mt-4">
                        <p class="text-gray-700 dark:text-gray-300">Escanea este código QR con tu aplicación de autenticación para completar la configuración de 2FA:</p>
                        <img src="<?php echo $qrCodeUrl; ?>" alt="Código QR 2FA">
                    </div>
                <?php endif; ?>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold p-3 rounded-xl transition-all">Guardar Cambios</button>
            </form>
        </div>
    </div>
</body>
</html>
