<?php
session_start();
require 'config.php';
header("Content-Type: text/html; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Manejar la actualización de la URL original
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $short_code = $_POST['short_code'];
    $new_url = $_POST['new_url'];
    
    $update_query = "UPDATE urls SET original_url = ? WHERE short_code = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $new_url, $short_code, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Manejar la eliminación de una URL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $short_code = $_POST['short_code'];
    
    $delete_query = "DELETE FROM urls WHERE short_code = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("si", $short_code, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Obtener todos los acortadores del usuario
$query = "SELECT short_code, original_url FROM urls WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$links = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar URLs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <?php include 'header.php'; ?>

    <div class="flex justify-center mt-6">
        <div class="w-full max-w-3xl bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Administrar URLs Acortadas</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Edita o elimina tus enlaces fácilmente.</p>

            <table class="w-full border-collapse border border-gray-300 dark:border-gray-600 mt-4">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="p-2 border border-gray-300 dark:border-gray-600">URL Acortada</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600">URL Original</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($links as $link): ?>
                        <tr class="border border-gray-300 dark:border-gray-600">
                            <td class="p-2">
                                <a href="https://www.latinbattle.com/url/<?php echo htmlspecialchars($link['short_code']); ?>" target="_blank" class="text-blue-500 hover:underline">
                                    <?php echo htmlspecialchars($link['short_code']); ?>
                                </a>
                            </td>
                            <td class="p-2">
                                <form method="POST" class="flex">
                                    <input type="hidden" name="short_code" value="<?php echo $link['short_code']; ?>">
                                    <input type="text" name="new_url" value="<?php echo htmlspecialchars($link['original_url']); ?>" class="border p-1 w-full">
                                    <button type="submit" name="update" class="bg-blue-500 text-white px-2 ml-2 rounded">Guardar</button>
                                </form>
                            </td>
                            <td class="p-2">
                                <form method="POST">
                                    <input type="hidden" name="short_code" value="<?php echo $link['short_code']; ?>">
                                    <button type="submit" name="delete" class="bg-red-500 text-white px-2 rounded">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
