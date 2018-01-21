<?php

header("Content-type: image/png");

$z = $_GET["z"]; $x = $_GET["x"]; $y = $_GET["y"]; $h = $_GET["h"];
$string = $z . "/" . $x . "/" . $y;

$iwidth = 256;
$iheight = $h;

// create empty image

$img = ImageCreateTrueColor($iwidth,$iheight);

$transparentCol = imagecolorallocate($img, 255, 255, 0);
imagefilledrectangle($img,0,0,$iwidth,$iheight,$transparentCol);
imagecolortransparent($img, $transparentCol);

// write text

$font = 16;
$width = imageFontWidth($font) * strlen($string) ;
$height = imageFontHeight($font) ;
$x = imagesx($img) - $width ;
$y = imagesy($img) - $height;
$backgroundColor = imagecolorallocate ($img, 255, 255, 255);
$textColor = imagecolorallocate ($img, 0, 0, 0);
imageString ($img, $font, $x-1, $y-1, $string, $backgroundColor);
imageString ($img, $font, $x-1, $y+1, $string, $backgroundColor);
imageString ($img, $font, $x+1, $y+1, $string, $backgroundColor);
imageString ($img, $font, $x+1, $y-1, $string, $backgroundColor);
imageString ($img, $font, $x, $y, $string, $textColor);

ImagePng($img);

?>
