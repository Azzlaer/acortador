<?php
    /* Configuración para conexión a la base de datos y dominio principal
    1. Asegúrate de haber cambiado los valores de $domain, $host, $user, $pass y $db
       para que reflejen la configuración de tu servidor.
    */

    // La URL de tu dominio principal (termina con una barra '/')
    $domain = "https://www.latinbattle.com/url/";

    // Configuración de la base de datos
    $host = "127.0.0.1"; // Puede ser 'localhost' o la IP de tu servidor
    $user = "latinbat_url"; // Usuario de la base de datos
    $pass = "QVwyVgtJqNJH"; // Contraseña de la base de datos
    $db = "latinbat_url";   // Nombre de la base de datos

    // Conexión a la base de datos
    $conn = mysqli_connect($host, $user, $pass, $db);
    if (!$conn) {
        die("Error de conexión a la base de datos: " . mysqli_connect_error());
    }
?>
