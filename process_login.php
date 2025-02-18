<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require 'config.php';
require 'GoogleAuthenticator.php'; // Incluir la clase para la autenticación 2FA

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Buscar al usuario en la base de datos
    $stmt = $conn->prepare("SELECT id, password, two_fa, two_fa_secret FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $stored_password, $two_fa, $two_fa_secret);
    $stmt->fetch();
    $stmt->close();

    // Verificar si se encontró el usuario y la contraseña es válida
    if ($user_id && password_verify($password, $stored_password)) {
        if ($two_fa) {
            // Verificar el código 2FA si está activado
            $ga = new PHPGangsta_GoogleAuthenticator();
            $two_fa_code = $_POST['two_fa_code'] ?? '';
            $isCodeValid = $ga->verifyCode($two_fa_secret, $two_fa_code, 2); // Permitir 2 minutos de tolerancia

            if (!$isCodeValid) {
                $_SESSION['error'] = "Código de autenticación 2FA inválido.";
                header("Location: login.php");
                exit;
            }
        }

        // Autenticación exitosa, establecer la sesión
        $_SESSION['user_id'] = $user_id;
        header("Location: user_dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Nombre de usuario o contraseña incorrectos.";
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
