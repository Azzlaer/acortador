<?php
/**
 * PHP QR Code encoder
 * @version 1.1.4
 * 
 * Copyright (C) 2010 Dominik Dzienia
 * 
 * Based on libqrencode C library distributed under LGPL 2.1
 * 
 * The PHP QR Code is distributed under LGPL 3
 * 
 * PHP QR Code is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

define('QR_ECLEVEL_L', 0);
define('QR_ECLEVEL_M', 1);
define('QR_ECLEVEL_Q', 2);
define('QR_ECLEVEL_H', 3);

class QRcode
{
    public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4)
    {
        include_once 'qrimage.php'; // Incluir funciones de generación de imagen
        QRimage::png($text, $outfile, $level, $size, $margin);
    }
}
