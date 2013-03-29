<? $x = $_GET["x"]; $y = $_GET["y"]; ?>

<?
$myfile = "/var/www/osm/orders";
$fh = fopen($myfile, 'a') or die("can't open file!");
$toWrite = $x . " " . $y . "\n"; 
fwrite($fh, $toWrite);
?>

<html>
<head><title>Regenerate Tile <?=$x?>/<?=$y?></title></head>
<body>

<p>
Request sent. It can take some hours until tile <?=$x?>/<?=$y?> will be rerendered.
Please be patient.
</p>

<p>
<a href="../browse/?mode=xxx&x=<?=$x?>&y=<?=$y?>">Back</a>
</p>

</body>
</html>
