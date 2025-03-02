<?php
class QRimage
{
    public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4)
    {
        $image = imagecreate(100, 100); // Puedes modificar el tamaño según tu necesidad
        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, 100, 100, $white);
        imagestring($image, 5, 10, 10, $text, $black); // Solo imprime texto de prueba

        if ($outfile) {
            imagepng($image, $outfile);
        } else {
            header("Content-Type: image/png");
            imagepng($image);
        }

        imagedestroy($image);
    }
}
