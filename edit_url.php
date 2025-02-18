<?php
session_start();
include 'config.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM urls WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$url = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_url = trim($_POST['url']);
    $update_query = "UPDATE urls SET original_url = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $new_url, $id);
    $stmt->execute();
    header('Location: user_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar URL</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded shadow-md">
        <h1 class="text-xl font-bold mb-4">Editar URL</h1>
        <form action="edit_url.php?id=<?php echo $id; ?>" method="POST">
            <div class="mb-4">
                <label for="url" class="block font-medium">Nueva URL</label>
                <input type="text" id="url" name="url" value="<?php echo $url['original_url']; ?>" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Guardar cambios</button>
        </form>
    </div>
</body>
</html>
