<?

$base = "../tiles/";

//parse_str(implode('&', array_slice($argv, 1)), $_GET);

$x = $_GET["x"]; 
$y = $_GET["y"];

$z = $_GET["z"]; 
$dir = $_GET["dir"];

$fac = pow(2, $z-13);

$jpg_file = $base . $dir . "/" . $z . "/" . $x . "/" . $y . ".jpg";
$png_file = $base . $dir . "/" . $z . "/" . $x . "/" . $y . ".png";
$pack = $base . $dir . "/packed/" . (int) ($x/$fac) . "/" . (int) ($y/$fac) . ".pack";

if ((intval($z) > 13) && file_exists($pack)) {

   header("Content-type: image/png");

   $handle = fopen($pack, "rb");
   $content = fread($handle, 1364*4);
   fclose($handle);
   $offsets = unpack("L*", $content);

   $u = $x - (int)($x / $fac) * $fac;
   $v = $y - (int)($y / $fac) * $fac;
   
   $idx = $u + $v*$fac + 1;

   switch($z) {
   case 18:
	$start = $offsets[$idx];
	$end = $offsets[$idx+1];
	break;
   case 17:
   	$idx += 1024;
	$start = $offsets[$idx];
	$end = $offsets[$idx+1];
	break;
   case 16:
   	$idx += 1280;
	$start = $offsets[$idx];
	$end = $offsets[$idx+1];
	break;
   case 15:
   	$idx += 1344;
	$start = $offsets[$idx];
	$end = $offsets[$idx+1];
	break;
   case 14:
   	$idx += 1360;
	$start = $offsets[$idx];
	if (($u == 1) && ($v == 1)) {
	   $end = filesize($pack);
	} else {
	   $end = $offsets[$idx+1];
	}
	break;
   default:
	die("No valid zoom");
   }

   echo file_get_contents($pack, NULL, NULL, $start, $end-$start);

} elseif (file_exists($png_file)) {

   header("Content-type: image/png");
   readfile($png_file);

} else {

   header("Content-type: image/jpeg");
   readfile($jpg_file);
}

?>
