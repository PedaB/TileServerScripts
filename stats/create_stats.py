#!/usr/bin/python
# -*- coding: utf-8 -*-

import locale, os
from time import *

RRD_DIR = "/tmp/rrd/"
RRD_FILE = RRD_DIR + "tiles.rrd"

#
# Doing the plot, taking a template for rrdtool graph
#
def plot_work(template, file, title, start, end, extra="", check=-1):

    if check == -1:
        check = end

    if os.path.exists(file):
        fd = os.stat(file)
    else:
        fd = -1
    
    if fd == -1 or fd.st_mtime < check:
        # TODO: add --lazy once this works
        command = "rrdtool graph %s --width 950 --height 250 " \
                  "--start %d --end %d --title \"%s\" %s "
        
        command = command % (file, start, end, title, extra)
        command += template + " >/dev/null"

	print(command)
	os.system(command)


#
# Ploting average tile states
# 
def plot_avg(file, title, start, end, check=-1):
    trend = int((end - start) / 3)
    defstart = int(start - trend)
    lineDef = "DEF:ogl=" + RRD_FILE + ":ogltiles:LAST " \
              "DEF:queue=" + RRD_FILE + ":tilequeue:LAST:start=" + str(defstart) + " " \
              "CDEF:trend=queue," + str(trend) + ",TREND " \
              "CDEF:prev=PREV\(queue\) " \
              "CDEF:r_tmp=prev,queue,- " \
              "CDEF:rate=r_tmp,0,LT,PREV,r_tmp,IF " \
              "CDEF:scaled=rate,50,* " \
              "CDEF:srtrend=scaled,10800,TREND " \
              "VDEF:q_min=queue,MINIMUM " \
              "VDEF:q_avg=queue,AVERAGE " \
              "VDEF:q_max=queue,MAXIMUM " \
              "VDEF:r_min=rate,MINIMUM " \
              "VDEF:r_avg=rate,AVERAGE " \
              "VDEF:r_max=rate,MAXIMUM " \
              "AREA:ogl#CCCCFF:\"Queue size \t\t\t\t \" " \
              "LINE:ogl#000040: " \
              "AREA:queue#FF8888: " \
              "LINE:queue#FF0000: " \
              "LINE:trend#000000: " \
              "LINE1.5:srtrend#00CC00:\"Render rate (per hour)\l\" " \
              "COMMENT:\"Minimum\: \" " \
              "GPRINT:q_min:\"%6.0lf \t\t    \" " \
              "GPRINT:r_min:\"%6.0lf \l\" " \
              "COMMENT:\"Average\: \" " \
              "GPRINT:q_avg:\"%6.0lf \t\t    \" " \
              "GPRINT:r_avg:\"%6.0lf \l\" " \
              "COMMENT:\"Maximum\: \" " \
              "GPRINT:q_max:\"%6.0lf \t\t    \" " \
              "GPRINT:r_max:\"%6.0lf \l\" "
    
    extra = "  --right-axis 0,02:0 --right-axis-label \"render rate\" --right-axis-format \" %3.0lf \" "

    plot_work(lineDef, file, title, start, end, extra, check)


#
# plot a day, week, month starting at start with “text (date)” as label
#
def plot_day(file, text, start, check = -1):
    dayname = strftime("%A, %d. %B %Y", localtime(start))
    plot_avg(file, "%s (%s)" % (text, dayname), start, start + day, check)

def plot_week(file, text, start, check = -1):
    weekname = strftime("%V. Woche %Y", localtime(start))
    plot_avg(file, "%s (%s)" % (text, weekname), start, start + week, check)

def plot_month(file, start, end, check = -1):
    monthname = strftime("%B %Y", localtime(start))
    plot_avg(file, monthname, start, end, check)

def plot_year(file, start, end, check = -1):
    yearname = strftime("%Y", localtime(start))
    plot_avg(file, yearname, start, end, check)



#
# main
#

locale.setlocale(locale.LC_ALL, "de_DE.utf8")
os.environ['LANG'] = "de_DE"

os.chdir(RRD_DIR)

day = 60*60*24;
week = 60*60*24*7
now = localtime(time())

#
# Tagesgraphen
#
today = mktime([now.tm_year, now.tm_mon, now.tm_mday, 0, 0, 0, 0, 0, -1])

plot_day("tiles-heute.png", "Heute", today)
plot_day("tiles-gestern.png", "Gestern", today - day, today)
plot_day("tiles-vorgestern.png", "Vorgestern", today - 2 * day, today)
plot_day("tiles-vorvorgestern.png", "Vorvorgestern", today - 3 * day, today)

#
# Wochengraphen
#
monday = today - now.tm_wday * day
plot_week("tiles-diese-woche.png", "Diese Woche", monday)
plot_week("tiles-letzte-woche.png", "Letzte Woche", monday - week, monday)
plot_week("tiles-vorletzte-woche.png", "Vorletzte Woche", monday - 2 * week, monday)
plot_week("tiles-vorvorletzte-woche.png", "Vorvorletzte Woche", monday - 3 * week, monday)

#
# Monatsgraphen
#
this_month = mktime([now.tm_year, now.tm_mon,   1, 0, 0, 0, 0, 0, -1])
prev_month = mktime([now.tm_year, now.tm_mon-1, 1, 0, 0, 0, 0, 0, -1])
pprev_month = mktime([now.tm_year, now.tm_mon-2, 1, 0, 0, 0, 0, 0, -1])
ppprev_month = mktime([now.tm_year, now.tm_mon-3, 1, 0, 0, 0, 0, 0, -1])
next_month = mktime([now.tm_year, now.tm_mon+1, 1, 0, 0, 0, 0, 0, -1])

plot_month("tiles-diesen-monat.png", this_month, next_month)
plot_month("tiles-letzten-monat.png", prev_month, this_month, this_month)
plot_month("tiles-vorletzten-monat.png", pprev_month, prev_month, this_month)
plot_month("tiles-vorvorletzten-monat.png", ppprev_month, pprev_month, this_month)

#
# Jahresgraph
#
this_year = mktime([now.tm_year, 1, 1, 0, 0, 0, 0, 0, -1])
next_year = mktime([now.tm_year+1, 1, 1, 0, 0, 0, 0, 0, -1])
prev_year = mktime([now.tm_year-1, 1, 1, 0, 0, 0, 0, 0, -1])

plot_year("tiles-dieses-jahr.png", this_year, next_year)
plot_year("tiles-letztes-jahr.png", prev_year, this_year)
