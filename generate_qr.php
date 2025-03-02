<?php
require 'vendor/autoload.php';
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

$short_code = isset($_GET['code']) ? $_GET['code'] : '';

// Genera la URL para el código QR
$url = "https://www.latinbattle.com/url/" . $short_code;

$qrCode = Builder::create()
    ->writer(new PngWriter())
    ->data($url)
    ->size(200)
    ->build();

// Enviar la imagen generada al navegador
header('Content-Type: image/png');
echo $qrCode->getString();
?>
