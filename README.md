# bandwidthcatch
Capture and aggregate the bandwidth available to users by geolocation.

##Current Status
Code is *not* ready for use. The Javascript speed sampling implementation is incomplete.

##Intended Use
Toss these files in a webserver and send participants to submitData.php.
The code will submit a speed sample to collectData.php every minute.
collectData.php will aggregate those data submissions into csv files,
and it will create a new file for each day.

##Configuration
All program options live in isp-options.php and can be adjusted there.
Since the default values are not very useful, please adjust them before
attempting to gather data.
