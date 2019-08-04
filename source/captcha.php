<?php

header("content-type: image/png");
session_start();
session_regenerate_id();


$word = [];
const BACKGROUND_COLOR = ["R" => "181", "G" => "181", "B" => "181"]; // 8background color in RGB
const TEXT_COLOR = ["R" => "0", "G" => "154", "B" => "242"];  // text color in RGB
const LINE_COLOR = ["R" => "249", "G" => "255", "B" =>"249"];  // cross lines color in RGB

if (isset($_GET['len'])) {
    $length = (INT) $_GET['len'];
    if($length < 4){
        $length = 4;
    }
    if($length > 8){
        $length = 8;
    }
}else{
    $length = 4;
}
if (isset($_GET['lng'])) {
    if ($_GET['lng'] == 'ar') {
        $word = getArbicWord();
        $language = 'ar';
    } elseif ($_GET['lng'] == 'en') {
        $word = getEnglishWord();
        $language = 'en';
    } else {
        $word = getEnglishWord();
        $language = 'en';
    }
} else {
    $word = getEnglishWord();
    $language = 'en';
}

$wtmp = '';
for ($i = 0; $i < $length; $i++) {
    $wtmp .= $word[$i];
}
$_SESSION['captchId'] = $wtmp;

function getArbicWord() {
    $temp = [];
    global $length;
    $char = ['أ', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    for ($i = 0; $i < $length; $i++) {
        $temp[] = $char[mt_rand(0, 37)];
    }
    return $temp;
}

function getEnglishWord() {
    $temp = [];
    global $length;
    $char = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    for ($i = 0; $i < $length; $i++) {
        $temp[] = $char[mt_rand(0, 33)];
    }
    return $temp;
}

$size = 56;
$deg = 10;
$x = 10;
$y = 80;
$font = '';
$width = ($length * 40) + 30;
$step = 0;
if ($language == 'ar') {
    $font = 'font/M Unicode Susan.ttf';
    $step = -45;
    $x = $width - 40;
} else {
    $font = 'font/CHILLER.TTF';
    $step = 40;
    $x = 15;
}
$img = imagecreate($width, 120);
$backgroundColor = imagecolorallocate($img, BACKGROUND_COLOR['R'], BACKGROUND_COLOR['G'], BACKGROUND_COLOR['B']);
$textColor = ImageColorAllocate($img, TEXT_COLOR['R'], TEXT_COLOR['G'], TEXT_COLOR['B']);
$lineColor = imagecolorallocate($img, LINE_COLOR['R'], LINE_COLOR['G'], LINE_COLOR['B']);

for ($i = 0; $i < 6; $i++) {
    imagesetthickness($img, rand(1, 4));
    imageline($img, 0, rand(0, 120), $width, rand(0, 120), $lineColor);
}
for ($i = 0; $i < 10; $i++) {
    imagesetthickness($img, rand(1, 4));
    imageline($img, rand(0, $width), 0, rand(0, $width), 120, $lineColor);
}
for ($i = 0; $i < $length; $i++) {
    $show = $word[$i];
    imagettftext($img, $size, $deg, $x, $y, $textColor, $font, $show);
    if ($size == 56) {
        $size = 64;
    } elseif ($size == 64) {
        $size = 56;
    }
    if ($deg == 10) {
        $deg = -15;
    } else {
        $deg = 10;
    }
    if ($y == 60) {
        $y = 90;
    } else {
        $y = 60;
    }
    $x += $step;
}

imagepng($img);
imagedestroy($img);
