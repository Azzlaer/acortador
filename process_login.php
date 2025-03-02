<?php
session_start();
require 'config.php';

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($username) || empty($password)) {
    $_SESSION['error_message'] = "Por favor, ingresa usuario y contraseña.";
    header("Location: login.php");
    exit;
}

// Buscar el usuario por nombre de usuario
$query = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user && password_verify($password, $user['password'])) {
    // Inicio de sesión exitoso
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: user_dashboard.php");
    exit;
} else {
    // Usuario o contraseña incorrectos
    $_SESSION['error_message'] = "Usuario o contraseña incorrectos.";
    header("Location: login.php");
    exit;
}
