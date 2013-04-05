nose-monitor
============

PHP Class for retrieving data from "The Nose" Monitor by PureChoice

This class only works with the PureChoice "The Nose" Monitor

The class provides JSON output for the built in Temperature, Humidity, and CO2 Sensors

It converts the data into a more human readable format and alerts via email if a threshold is reached.

1.) Change 'private $url' to the IP of your monitor such as:
Example URL:    
    private $url = "http://xxx.xxx.xxx.xxx/data.txt";

2.) Change the $to value in sendAlert():
Example Email:
    $to = "example@gmail.com";
Example SMS:
    $to = "5555555555@provider.sms.gateway.com";

3.) To run continually setup a cron job in /etc/crontab or /etc/cron.d/nose
Example Cron:
5 * * * * root /usr/bin/php /dir/where/you/have/nose.php

Example without noisy output:
5 * * * * root /usr/bin/php /dir/where/you/have/nose.php > /dev/null 2>&1

Enjoy!
