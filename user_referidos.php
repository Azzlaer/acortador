<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener el código de referido del usuario
$query = "SELECT referral_code FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$referral_code = $user['referral_code'];
$stmt->close();

// Consultar los referidos del usuario logueado
$query = "
    SELECT username, email, created_at 
    FROM users 
    WHERE referred_by = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$referidos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
<?php include 'header.php'; ?>
<div class="flex justify-center mt-6">
    <div class="w-full max-w-3xl bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mis Referidos</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-4">Comparte tu código de referido para ganar recompensas.</p>

        <div class="mb-6">
            <label class="block text-gray-700 dark:text-gray-300 font-semibold">Tu código de referido:</label>
            <input type="text" value="<?php echo htmlspecialchars($referral_code); ?>" readonly class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 dark:text-gray-300 font-semibold">Enlace para compartir:</label>
            <input type="text" value="https://www.latinbattle.com/url/register.php?ref=<?php echo htmlspecialchars($referral_code); ?>" readonly id="refLink" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
            <button onclick="copyReferral()" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white p-2 rounded">Copiar enlace</button>
        </div>

        <?php if (count($referidos) > 0): ?>
            <ul>
                <?php foreach ($referidos as $ref): ?>
                    <li class="border-b border-gray-300 py-2">
                        <?php echo htmlspecialchars($ref['username']); ?> - <?php echo htmlspecialchars($ref['email']); ?>
                        <span class="text-gray-500 text-sm"> (Fecha: <?php echo htmlspecialchars($ref['created_at']); ?>)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">Aún no tienes referidos.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function copyReferral() {
    let copyText = document.getElementById("refLink");
    copyText.select();
    document.execCommand("copy");
    alert("Enlace copiado al portapapeles");
}
</script>
</body>
</html>
