<?php
require 'config.php';

$short_code = isset($_GET['code']) ? $_GET['code'] : null;

if (!$short_code) {
    header("Location: https://www.latinbattle.com");
    exit;
}

// Buscar la URL original según el short_code
$query = "SELECT id, original_url FROM urls WHERE short_code = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $short_code);
$stmt->execute();
$result = $stmt->get_result();
$url_data = $result->fetch_assoc();
$stmt->close();

if ($url_data) {
    $url_id = $url_data['id'];
    $original_url = $url_data['original_url'];

    // Registrar el clic en la tabla url_clicks
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $insert_click_query = "INSERT INTO url_clicks (url_id, ip_address, user_agent) VALUES (?, ?, ?)";
    $click_stmt = $conn->prepare($insert_click_query);
    $click_stmt->bind_param("iss", $url_id, $ip_address, $user_agent);
    if (!$click_stmt->execute()) {
        error_log("Error al registrar el clic: " . $click_stmt->error);
    }
    $click_stmt->close();

    // Actualizar el total de clics en la tabla url_statistics
    $update_stats_query = "
        INSERT INTO url_statistics (url_id, total_clicks, last_clicked)
        VALUES (?, 1, NOW())
        ON DUPLICATE KEY UPDATE 
        total_clicks = total_clicks + 1, 
        last_clicked = NOW()
    ";
    $stats_stmt = $conn->prepare($update_stats_query);
    $stats_stmt->bind_param("i", $url_id);
    if (!$stats_stmt->execute()) {
        error_log("Error al actualizar estadísticas: " . $stats_stmt->error);
    }
    $stats_stmt->close();

    // Redirigir a la URL original
    header("Location: " . $original_url);
    exit;
} else {
    // Si no se encuentra el código, redirigir a la página principal
    header("Location: https://www.latinbattle.com");
    exit;
}
