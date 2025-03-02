<?php
session_start();
require 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Procesar cambios en la configuración
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $birth_date = $_POST['birth_date'] ?? '';
    $discord = $_POST['discord'] ?? '';
    $facebook = $_POST['facebook'] ?? '';
    $youtube = $_POST['youtube'] ?? '';
    $tiktok = $_POST['tiktok'] ?? '';
    $twitter = $_POST['twitter'] ?? '';
    $paypal = $_POST['paypal'] ?? '';
    $twitch = $_POST['twitch'] ?? '';

    $update_query = "UPDATE users SET email = ?, first_name = ?, last_name = ?, birth_date = ?, discord = ?, facebook = ?, youtube = ?, tiktok = ?, twitter = ?, paypal = ?, twitch = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssssssssi", $email, $first_name, $last_name, $birth_date, $discord, $facebook, $youtube, $tiktok, $twitter, $paypal, $twitch, $user_id);
    
    if ($stmt->execute()) {
        $message = "Información actualizada con éxito.";
    } else {
        $message = "Error al actualizar la información.";
    }
    $stmt->close();

    // Actualizar contraseña si se proporcionó
    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $update_password_query = "UPDATE users SET password = ? WHERE id = ?";
            $password_stmt = $conn->prepare($update_password_query);
            $password_stmt->bind_param("si", $hashed_password, $user_id);
            
            if ($password_stmt->execute()) {
                $message .= " Contraseña actualizada con éxito.";
            } else {
                $message .= " Error al actualizar la contraseña.";
            }
            $password_stmt->close();
        } else {
            $message .= " Las contraseñas no coinciden.";
        }
    }
}

// Obtener la información del usuario
$query = "SELECT username, email, first_name, last_name, birth_date, created_at, discord, facebook, youtube, tiktok, twitter, paypal, twitch FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="flex justify-center mt-6">
        <div class="w-full max-w-3xl bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Configuración de Usuario</h1>
            <?php if (!empty($message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form action="user_settings.php" method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Usuario:</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-200 dark:bg-gray-600" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Correo Electrónico:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Nombre:</label>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Apellido:</label>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Fecha de Nacimiento:</label>
                    <input type="date" name="birth_date" value="<?php echo htmlspecialchars($user['birth_date'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Discord:</label>
                    <input type="text" name="discord" value="<?php echo htmlspecialchars($user['discord'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Facebook:</label>
                    <input type="text" name="facebook" value="<?php echo htmlspecialchars($user['facebook'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">YouTube:</label>
                    <input type="text" name="youtube" value="<?php echo htmlspecialchars($user['youtube'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">TikTok:</label>
                    <input type="text" name="tiktok" value="<?php echo htmlspecialchars($user['tiktok'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                
                <div>
                <label class="block text-gray-700 dark:text-gray-300">Twitter:</label>
                <input type="text" name="twitter" value="<?php echo htmlspecialchars($user['twitter'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                
                <div>
                <label class="block text-gray-700 dark:text-gray-300">Paypal:</label>
                <input type="text" name="paypal" value="<?php echo htmlspecialchars($user['paypal'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                
                 <div>
                <label class="block text-gray-700 dark:text-gray-300">Twitch:</label>
                <input type="text" name="twitch" value="<?php echo htmlspecialchars($user['twitch'] ?? ''); ?>" class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-700" />
                </div>
                
                
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">Guardar Cambios</button>
            </form>
        </div>
    </div>
</body>
</html>
