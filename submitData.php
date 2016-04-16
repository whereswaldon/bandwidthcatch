<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ISP Data Collector</title>
  <meta name="description" content="A data collector to sample internet speeds.">
  <meta name="author" content="Christopher Waldon">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <form>
                <fieldset>
                    <legend>ISP Data</legend>

                    <?php
                        require('isp-options.php');
                    ?>
                    <label class="control-label" for="isp_name">Internet Service Provider</label>
                    <select required id="isp_name" class="form-control" name="isp_name">
                        <option value="none">Select a provider</option>
                    <?php
                        foreach($isp_options as $option) {
                            echo "<option value=\"$option\">$option</option>";
                        }
                    ?>
                    </select>
                    <p class="help-block">This is the company that you pay for internet access, unless you live on campus. If you live on campus, please select "Campus." </p>

                    <label class="control-label" for="paid_bandwidth">Paid Speed (leave blank if unknown)</label>
                    <input type="number" class="form-control" id="paid_bandwidth" name="paid_bandwidth"
                    min="<?= $min_bandwidth ?>" max="<?= $max_bandwidth ?>">
                    <p class="help-block">How many Megabits per second are you paying for? It's fine if you don't know, just leave this blank.</p>

                    <h3>Geolocation</h3>
                    <p class="help-block">We're trying to gather this data completely anonymously, but we want to be able to tell your data apart from the data of other people. Instead of using your name or something else that's personally identifiable, we'd like to use your GPS coordinates. If you could just look them up and copy & paste them below, we'd really appreciate it.</p>

                    <p>
                    <a class="btn btn-success" title="GPS Coordinate Lookup" href="http://www.gps-coordinates.net/" target="_blank">Look up Coordinates</a>
                    </p>

                    <label class="control-label" for="gps_north">Latitude (North)</label>
                    <input required class="form-control" type="number" name="gps_north" id="gps_north"
                    min="<?= $min_north ?>" max="<?= $max_north ?>">
            <br>

                    <label class="control-label" for="gps_east">Longitude (East)</label>
                    <input required class="form-control" type="number" name="gps_east" id="gps_east"
                    min="<?= $min_east ?>" max="<?= $max_east ?>">
            <br>

                    <button class="btn btn-info" id="begin-button" type="button">Begin Collection</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<script>
    function ready(fn) {
        if (document.readyState != 'loading'){
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    /*
     * Shim for Date.now(). Hopefully shouldn't need it.
     */
    if (!Date.now) {
        Date.now = function now() {
            return new Date().getTime();
        };
    }

    /*
     * Ready the page to send data.
     */
    ready(function go() {
        var begin_button = document.getElementById('begin-button');
        var isp_name_input = document.getElementById('isp_name');
        var paid_bandwidth_input = document.getElementById('paid_bandwidth');
        var gps_north_input = document.getElementById('gps_north');
        var gps_east_input = document.getElementById('gps_east');
        var download_speed = undefined;
        var last_speed = undefined;

        /*
         * Ensure that all required values in the form are present
         */
        var validateInputs = function() {
            return (isp_name_input.value != 'none'
                && gps_north_input.value != ''
                && gps_east_input.value != '');
        };

        /*
         * Update the value of download_speed to a new sampling
         */
        var measureSpeed = function() {
            last_speed = download_speed;
            var start_time, end_time = undefined;
            var test = new Image();
            test.onload = function loadImage() {
                end_time = Date.now();
                download_speed = <?= $image_size ?> / (end_time - start_time); //bytes per second
                console.log(download_speed);
                sendReport();
            }
            test.onerror = function loadError() {
                console.log("error loading image.");
                end_time = -1;
            }
            var suffix = '?nnn=' + Date.now();
            var source_string = '<?= $image_path ?>?nnn=' + Date.now();

            start_time = Date.now();
            test.src = source_string;
        };

        /*
         * Send the lastest data to be collected.
         */
        var sendReport = function() {
            if (download_speed == undefined || last_speed == download_speed) {
                return;
            }
            var data = new FormData();
            data.append('isp_name', isp_name_input.value);
            data.append('gps_north', gps_north_input.value);
            data.append('gps_east', gps_east_input.value);
            data.append('download_time', download_speed);
            data.append('timestamp', Date.now());
            data.append('paid_bandwidth', paid_bandwidth_input.value);
            var request = new XMLHttpRequest();
            request.open('POST', '<?= $post_request_target ?>', true);
            request.send(data);

        };
        
        /*
         * Kick off the reporting process once every minute
         */
        var beginReporting = function() {
            if (! validateInputs()) {
                alert("Part of the form is incomplete. Could you doublecheck that you've "
                    + "selected an ISP and your GPS coordinates and then try again?");
                return;
            }
            alert("Thank you! Please just leave this tab open and go back to your browsing.");
            measureSpeed();
            var intervalID = window.setInterval(function handleData() {
                measureSpeed();
            }, 60000);
        };

        begin_button.addEventListener('click', beginReporting, false);
    });

</script>
</body>
</html>
