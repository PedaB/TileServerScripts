<? $x = $_GET["x"]; $y = $_GET["y"]; ?>

<html>
<head><title>Regenerate Tile <?=$x?>/<?=$y?></title></head>
<body>

<p>Current Tile:</p>
<p>
<table border="0">
    <tr>
	<td></td>
	<td><center>
		<a href="tiles.php?x=<?=$x?>&y=<?=$y-1?>">Up</a>
	</center></td>
	<td></td>
    </tr>
    <tr>
	<td><a href="tiles.php?x=<?=$x-1?>&y=<?=$y?>">Left</a></td>
	<td><img width="256" height="256" src="mytiles/povtiles/13/<?=$x?>/<?=$y?>.png"/></td>
	<td><a href="tiles.php?x=<?=$x+1?>&y=<?=$y?>">Right</a></td>
    </tr>
    <tr>
	<td></td>
	<td><center>
		<a href="tiles.php?x=<?=$x?>&y=<?=$y+1?>">Down</a>
	</center></td>
	<td></td>
    </tr>
</table>
</p>

<p>Last Updated: TODO</p>


<form action="update.php" method="GET">
<input type="hidden" name="x" value="<?=$x?>">
<input type="hidden" name="y" value="<?=$y?>">
<input type="submit" value="ReRender"/>
</form>





</body>
</html>

