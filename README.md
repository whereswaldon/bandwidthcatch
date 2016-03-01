# bandwidthcatch
Capture and aggregate the bandwidth available to users by geolocation.

##Current Status
Code is *not* ready for use. The Javascript speed sampling implementation is incomplete.

##Installation
Follow these steps to get an instance of this data collection system running
locally. There are much better ways to deploy this, but they require access
to servers. These instruction assume that you're on a Unix machine (Mac OS 
X or Linux). They will not work for Windows machines. I'm sure that it's 
possible to run this from Windows with similar steps. If you know how to do
it, submit a PR for this documentation.

###PHP
The majority of the code is written in PHP. If you have php installed, 
you'll need a newer version of it. To check your php version, run
```bash
php --version
```
If your version is older than php 5.4, God help you. These instructions
will not work for you, so you'll need to install a newer version.

If you don't have php installed, don't worry. There are plenty of good
instructions out there for how to install it. One word of caution: Do
NOT attempt to build it from source unless you really know what you're
doing. I would advise installing from a package manager like apt (on
Ubuntu) or Homebrew (Mac OS X).

###ngrok
Since it's nice not to need a server in order to test/develop this, I advise
using ngrok to allow communication with your machine over HTTP. If you think
that you might already have ngrok installed, run the following to find out
```bash
ngrok version
```
If you have it, move on. If not, go to
[ngrok's website](https://ngrok.com/) to install it. 

###Directory structure
In your system's terminal, go to some folder that you'd like to use for this
application.
```bash
cd /path/to/where/you/want/to/install/this
```
Then, grab a copy of the code.
```bash
git clone https://github.com/whereswaldon/bandwidthcatch.git
```
Adjust the settings in isp-options.php to whatever you need them to be.
Then run
```bash
php -S localhost:8000 #you can use any number higher than 7999
```
Then, in a new terminal tab, run
```bash
ngrok http 8000 #use the same number as the last command
```
You'll see a screen with information from ngrok. Find the forwarding address
that starts with http:// (should look like http://92832de0.ngrok.io), and
paste it into the URL bar of your web browser. At the end of that URL, add
"/submitData.php" (ex: http://92832de0.ngrok.io/submitData.php). You should
see a form titled "ISP Data". 

If you fill out that form and submit it, the collectData.php script will
start a CSV file named after the current date and insert the data that
you submitted. Subsequent data submissions will be appended to that file
until midnight, at which point it will start a new file for the new
day.

##Intended Use
Toss these files in a webserver and send participants to submitData.php.
The code will submit a speed sample to collectData.php every minute.
collectData.php will aggregate those data submissions into csv files,
and it will create a new file for each day.

##Configuration
All program options live in isp-options.php and can be adjusted there.
Since the default values are not very useful, please adjust them before
attempting to gather data.
