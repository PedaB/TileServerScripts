#!/usr/bin/python
# -*- coding: utf-8 -*-

import os, math, getopt, sys, re

STOP_FILE = '/tmp/stop_tilegen'
INPUT_DIR = '/tmp/geo/'
MAX_THREADS = 4

def main():

    if os.path.exists(STOP_FILE):
        sys.exit();

    command = 'ps aux|grep generate_tile.py|grep -v grep|wc -l'
    nrThreads = int(os.popen(command).read())

    if nrThreads >= MAX_THREADS:
        print('Currently running threads: ' + str(nrThreads))
        print('Waiting for next round.')
        sys.exit();

    files = os.popen('ls -tr %s/*.pbf' % INPUT_DIR).readlines()

    if len(files) == 0:
        print('No pending work');
        sys.exit();

    print(str(len(files)) + ' tiles to go...')

    nextTile = files[0].strip();

    movedTile = "/tmp/" + nextTile[nextTile.rfind("/"):];
    command = 'mv %s %s' % (nextTile, movedTile)
    #print(command)
    os.system(command)
    
    reg = re.search('.*_([0-9]+)_([0-9]+).pbf', nextTile)
    nextX = int(reg.group(1))
    nextY = int(reg.group(2))

    logfile = '/tmp/run_%d_%d.log' % (nextX, nextY);
    command = './generate_tile.py %d %d %s > %s' % (nextX, nextY, movedTile, logfile);
    #print(command)
    os.system(command)
    os.remove(movedTile)
    #os.remove(logfile);

# call main...
main()
