<?

$file = "../../input/tiles/dl/germany.osm.pbf";

$string = date("Y-m-d H:i:s", filemtime($file));

echo $string;

?>
