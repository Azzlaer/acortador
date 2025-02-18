<?php
session_start();
include 'config.php';

$url = trim($_POST['url']);
$short_code = substr(md5(uniqid()), 0, 6);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$query = "INSERT INTO url (original_url, short_code, user_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssi', $url, $short_code, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'short_url' => $domain . $short_code]);
} else {
    echo json_encode(['success' => false]);
}
?>