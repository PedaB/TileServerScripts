<? $x = $_GET["x"]; $y = $_GET["y"]; ?>

<?
$myfile = "/tmp/orders";
$fh = fopen($myfile, 'a') or die("can't open file!");
$toWrite = $x . " " . $y . "\n"; 
fwrite($fh, $toWrite);
?>

<html>
<head><title>Regenerate Tile <?=$x?>/<?=$y?></title></head>
<body>

<p>
Request sent. It will take some hours until tile <?=$x?>/<?=$y?> will be rerendered.
Please be patient.
</p>

<p>
<a href="tiles.php?x=<?=$x?>&y=<?=$y?>">Back</a>
</p>

</body>
</html>
