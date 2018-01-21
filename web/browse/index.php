<?php
 $x = $_GET["x"]; $y = $_GET["y"]; $z = 13;
 $mode = $_GET["mode"];

 $base = "../tiles/n/";
 $src = "http://tiles.osm2world.org/osm/tiles/n/";
?>

<html>
<head>
	<title>Tile <?=$x?>/<?=$y?></title>
	<meta name="description" content="Tile browser with details for <?=$x?>/<?=$y?>" />
        <meta charset="UTF-8" />
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" media="screen,projection" href="detiles.css" />
</head>
<body>

<h1>Details for tile <?=$x?>/<?=$y?></h1>

<div id="tileview">
<table>
    <tr>
	<td></td>
	<td class="nav_cell"><center>
		<a href=".?<?php if ($mode=='xxx') { echo 'mode=xxx&'; } ?>x=<?=$x?>&y=<?=$y-1?>" style="display:block;">▲</a>
	</center></td>
	<td></td>
    </tr>
    <tr>
	<td class="nav_cell"><a href=".?<?php if ($mode=='xxx') { echo 'mode=xxx&'; } ?>x=<?=$x-1?>&y=<?=$y?>" style="display:block; height:100%">◀</a></td>
	<td><img src="http://tiles.osm2world.org/osm/tiles/n/<?=$z?>/<?=$x?>/<?=$y?>.png"/></td>
	<td class="nav_cell"><a href=".?<?php if ($mode=='xxx') { echo 'mode=xxx&'; } ?>x=<?=$x+1?>&y=<?=$y?>" style="display:block; height:100%">▶</a></td>
    </tr>
    <tr>
	<td></td>
	<td class="nav_cell"><center>
		<a href=".?<?php if ($mode=='xxx') { echo 'mode=xxx&'; } ?>x=<?=$x?>&y=<?=$y+1?>" style="display:block;">▼</a>
	</center></td>
	<td></td>
    </tr>
</table>
</div>

<div id="details">

<?php

function human_filesize($bytes, $decimals = 2) {
  $sz = ' kMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f ", $bytes / pow(1000, $factor)) . @$sz[$factor];
}

$file = $base . $z . "/" . $x . "/" . $y . ".png";
$imagesize = getimagesize($file);

$n = pow(2, $z);
$lon_deg = $x / $n * 360.0 - 180.0;
$lat_deg = rad2deg(atan(sinh(pi() * (1 - 2 * $y / $n))));

$runlog = "../log/run_".$x."_".$y.".log";
$gclog = "../log/gc_".$x."_".$y.".log";
$hproflog = "../log/hprof_".$x."_".$y.".log";
$pbffile = "../../input/old/tiles_z13_".$x."_".$y.".pbf";

$loglist = "";
if (file_exists($runlog)) { $loglist .= '<a href="'.$runlog.'">run</a>'; } else { $loglist .= '<span class="inactive">run</span>'; }
$loglist .= ", ";
if (file_exists($gclog)) { $loglist .= '<a href="'.$gclog.'">gc</a>'; } else { $loglist .= '<span class="inactive">gc</span>'; }
$loglist .= ", ";
if (file_exists($hproflog)) { $loglist .= '<a href="'.$hproflog.'">hprof</a>'; } else { $loglist .= '<span class="inactive">hprof</span>'; }

$tiledata = "";
if (file_exists($pbffile)) { $tiledata .= '<a href="'.$pbffile.'">pbf file</a>'; } else { $tiledata .= '<span class="inactive">pbf file</span>'; }
?>

<table>
<tr><th>Path</th><td><a href="<?=$file?>"><?=$file?></a></td></tr>
<tr><th>Last updated</th><td><?=date("Y-m-d H:i:s", filemtime($file))?></td></tr>
<tr><th>File size</th><td><?=human_filesize(filesize($file))?>B</td></tr>
<tr><th>Resolution</th><td><?=$imagesize[0]?> × <?=$imagesize[1]?></td></tr>
<tr><th>Logfiles</th><td><?=$loglist?></tr>
<tr><th>Raw data</th><td><?=$tiledata?></tr>
</table>

<p>
<a href="http://maps.osm2world.org/?zoom=<?=$z?>&lat=<?=$lat_deg?>&lon=<?=$lon_deg?>">View on slippy map</a>
<p>

</div>

<?php
if ($mode == "xxx") {

	echo ('<div id="controls">');
	
	echo ('<p>');
	echo ('Queue:'.iterator_count(new DirectoryIterator('/var/www/input/tiles')));
	echo (', Requests: '.count(file('/var/www/osm/tiles_todo')));
	echo ('</p>');

	echo ('<form action="update.php" method="GET">');
	echo ('<input type="hidden" name="x" value="'.$x.'">');
	echo ('<input type="hidden" name="y" value="'.$y.'">');
	echo ('<input type="submit" value="ReRender"/>');
	echo ('</form>');
	
	echo ('</div>');
}
?>

</body>
</html>

