nose-monitor
============

PHP Class for retrieving data from "The Nose" Monitor by PureChoice<br/>

This class only works with the PureChoice "The Nose" Monitor<br/>

The class provides JSON output for the built in Temperature, Humidity, and CO2 Sensors<br/>

It converts the data into a more human readable format and alerts via email if a threshold is reached.<br/>

1.) Change 'private $url' to the IP of your monitor such as:<br/>
Example URL:<br/>
    private $url = "http://xxx.xxx.xxx.xxx/data.txt";<br/>

2.) Change the $to value in sendAlert():<br/>
Example Email:<br/>
    $to = "example@gmail.com";<br/>
Example SMS:<br/>
    $to = "5555555555@provider.sms.gateway.com";<br/>

3.) To run continually setup a cron job in /etc/crontab or /etc/cron.d/nose<br/>
Example Cron:<br/>
*/5 * * * * root /usr/bin/php /dir/where/you/have/nose.php<br/>

<br/>Example without noisy output:<br/>
*/5 * * * * root /usr/bin/php /dir/where/you/have/nose.php > /dev/null 2>&1<br/>

Enjoy!
