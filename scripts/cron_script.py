#!/usr/bin/python
# -*- coding: utf-8 -*-

import os, math, getopt, sys

STOP_FILE = '/tmp/stop_tilegen'
REMAINING_TILES = '/tmp/tiles_todo'
WEBSITE_ORDERS = '/tmp/orders'
MAX_THREADS = 4


def main():

    if os.path.exists(STOP_FILE):
        sys.exit();

    command = 'ps aux|grep generate_tile.py|grep -v grep|wc -l';
    nrThreads = int(os.popen(command).read());

    if nrThreads >= MAX_THREADS:
        print('Currently running threads: ' + str(nrThreads));
        print('Waiting for next round.')
        sys.exit();

    if os.path.exists(REMAINING_TILES):
        todo = open(REMAINING_TILES).readlines();
    else:
        todo = [];

    if os.path.exists(WEBSITE_ORDERS):
        newOnes = open(WEBSITE_ORDERS).readlines();
        todo.extend(newOnes);
        os.remove(WEBSITE_ORDERS);

    if len(todo) == 0:
        print('No pending work');
        sys.exit();

    nextX,nextY = todo[0].split(' ');
    nextX = int(nextX);
    nextY = int(nextY);
    rest = todo[1:];

    f = open(REMAINING_TILES, 'w');
    for i in rest:
        f.write(i);

    logfile = '/tmp/run_%d_%d.log' % (nextX, nextY);
    command = './generate_tile.py %d %d > %s 2>&1 &' % (nextX, nextY, logfile);
    os.system(command);
    #os.remove(logfile);

# call main...
main()
