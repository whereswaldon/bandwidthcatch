# bandwidthcatch
Capture and aggregate the bandwidth available to users by geolocation.

##Intended Use
Toss these files in a webserver and send participants to submitData.php.
The code will submit a speed sample to collectData.php every minute.
collectData.php will aggregate those data submissions into csv files,
and it will create a new file for each day.
