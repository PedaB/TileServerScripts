#!/bin/sh

datatiles=`ls /var/www/tiles/data | wc -l`
echo $datatiles >> num_unprocesssed_data.txt
ogltiles=`find /var/www/tiletest/tiles/ogltiles/13/ -name *png | wc -l`
echo $ogltiles >> num_ogltiles_13.txt
rrdtool update /var/www/stats/tiles.rrd N:$ogltiles:$datatiles

