<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include 'config.php';

// Obtener todas las URLs
$query = "SELECT * FROM url";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administración</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Panel de administración</h1>
        <table class="w-full bg-white border rounded shadow">
            <thead>
                <tr>
                    <th class="border p-2">ID</th>
                    <th class="border p-2">URL original</th>
                    <th class="border p-2">URL corta</th>
                    <th class="border p-2">Fecha de creación</th>
                    <th class="border p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border p-2"><?php echo $row['id']; ?></td>
                        <td class="border p-2"><?php echo $row['original_url']; ?></td>
                        <td class="border p-2"><?php echo $domain . $row['short_code']; ?></td>
                        <td class="border p-2"><?php echo $row['created_at']; ?></td>
                        <td class="border p-2">
                            <a href="edit_url.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Editar</a> |
                            <a href="delete_url.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:underline">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="logout.php" class="block mt-4 text-blue-500 hover:underline">Cerrar sesión</a>
    </div>
</body>
</html>
