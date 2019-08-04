<?php
require_once '/qrInclude/tcpdf_barcodes_2d.php';
header("content-type: image/png");
$data =$_GET['data'];
$barcodeobj = new TCPDF2DBarcode($data, 'QRCODE,H');
imagepng($barcodeobj->getBarcodePNG(6, 6, array(0,0,0)));

