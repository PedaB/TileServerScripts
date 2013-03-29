#!/usr/bin/python
# -*- coding: utf-8 -*-

import os, math, getopt, sys, re, random

STOP_FILE = '/tmp/stop_tilegen'
INPUT_DIR = '/home/osmuser/input/tiles/'
FINISHED_DIR = '/home/osmuser/input/old/'
MAX_THREADS = 3

def main():

    if os.path.exists(STOP_FILE):
        sys.exit();

    command = 'ps aux|grep generate_tile.py|grep -v grep|wc -l'
    nrThreads = int(os.popen(command).read())

    if nrThreads >= MAX_THREADS:
        print('Currently running threads: ' + str(nrThreads))
        print('Waiting for next round.')
        sys.exit();

    #files = os.popen('ls -tr %s/*.pbf' % INPUT_DIR).readlines()
    dirList = os.listdir(INPUT_DIR);
    files = filter(lambda x:x.endswith('.pbf'), dirList)

    if len(files) == 0:
        print('No pending work');
        sys.exit();

    print(str(len(files)) + ' tiles to go...')

    nextTile = INPUT_DIR + '/' + files[random.randint(0, len(files)-1)].strip();

    movedTile = FINISHED_DIR + nextTile[nextTile.rfind("/"):];
    command = 'mv %s %s' % (nextTile, movedTile)
    #print(command)
    if os.system(command) != 0:
        sys.exit();
    
    reg = re.search('.*_([0-9]+)_([0-9]+).pbf', nextTile)
    nextX = int(reg.group(1))
    nextY = int(reg.group(2))

    logfile = '/tmp/logs/run_%d_%d.log' % (nextX, nextY);
    command = './generate_tile.py %d %d %s > %s 2>&1 ' % (nextX, nextY, movedTile, logfile);
    #command +=  ' ; mv %s %s ' % (movedTile, '/data/tiledata/oldtiles/');
    command += ' && echo osm1 >> /home/osmuser/input/rendered '
    command = '( ' + command + ')&';

    print(command)
    os.system(command)

# call main...
main()
