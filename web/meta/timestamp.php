<?php

$base = "../tiles/" . $_GET["dir"] . "/";

header("Content-type: image/png");

$z = $_GET["z"]; $x = $_GET["x"]; $y = $_GET["y"];

if (intval($z) > 13) {
   $fac = pow(2, $z-13);
   $x = (int)($x / $fac);
   $y = (int)($y / $fac);
   $file = $base . "packed/" . $x . "/" . $y . ".pack";
} else {
  $file = $base . $z . "/" . $x . "/" . $y . ".png";
}
$string = date("Y-m-d H:i:s", filemtime($file));

$iwidth = 256;
$iheight = 128;

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
$y = imagesy($img) - 2 * $height;
$backgroundColor = imagecolorallocate ($img, 255, 255, 255);
$textColor = imagecolorallocate ($img, 0, 0, 0);
imageString ($img, $font, $x-1, $y-1, $string, $backgroundColor);
imageString ($img, $font, $x-1, $y+1, $string, $backgroundColor);
imageString ($img, $font, $x+1, $y+1, $string, $backgroundColor);
imageString ($img, $font, $x+1, $y-1, $string, $backgroundColor);
imageString ($img, $font, $x, $y, $string, $textColor);

//imageString ($img, $font, $x-50, $y-20, $file, $textColor);

ImagePng($img);

?>
