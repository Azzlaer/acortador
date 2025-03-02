<?php

ob_start(); // Evita problemas de headers
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

?>


<nav class="bg-gray-800 p-4 text-white flex justify-between items-center">
    <div class="text-lg font-semibold">Acortador de URLs</div>
    <div class="space-x-4">
        <a href="user_dashboard.php" class="hover:underline">Mis URLs</a>
        <a href="user_settings_url.php" class="hover:underline">Modificar</a>
        <a href="user_statistics.php" class="hover:underline">Estadisticas</a>
        <a href="user_advanced.php" class="hover:underline">Avanzado</a>
        <a href="user_info.php" class="hover:underline">Gana Dinero</a>
        <a href="user_settings.php" class="hover:underline">Configuracion</a>
        <a href="logout.php" class="hover:underline">Cerrar sesion</a>
    </div>
</nav>