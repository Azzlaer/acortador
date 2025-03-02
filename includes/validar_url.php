<?php

function validarURL($url) {
    // Validar formato de URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    
    // Comprobar si la URL responde correctamente
    $headers = @get_headers($url);
    if ($headers && strpos($headers[0], '200') !== false) {
        return true;
    }
    return false;
}

function acortarURL($url, $pdo) {
    if (!validarURL($url)) {
        return "URL inválida o inaccesible.";
    }
    
    // Generar código único
    $codigo = substr(md5(uniqid(rand(), true)), 0, 6);
    
    // Insertar en la base de datos usando sentencia preparada
    $stmt = $pdo->prepare("INSERT INTO urls (codigo, url) VALUES (:codigo, :url)");
    $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $stmt->bindParam(":url", $url, PDO::PARAM_STR);
    $stmt->execute();
    
    return "URL acortada: https://tudominio.com/$codigo";
}

// Uso de ejemplo:
// require 'conexion.php';
// echo acortarURL('https://example.com', $pdo);

?>
