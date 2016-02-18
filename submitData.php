<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ISP Data Collector</title>
  <meta name="description" content="A data collector to sample internet speeds.">
  <meta name="author" content="Christopher Waldon">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
<form>
    <fieldset>
        <legend>ISP Data</legend>

        <?php
            require('isp-options.php');
        ?>
        <label for="isp_name">Who is your Internet Service Provider?</label>
        <select required id="isp_name" name="isp_name">
            <option value="none">Select a provider</option>
        <?php
            foreach($isp_options as $option) {
                echo "<option value=\"$option\">$option</option>";
            }
        ?>
        </select>
<br>
        
        <label for="paid_bandwidth">How many Megabits per second are you paying for?
            (leave blank if unknown)</label>
        <input type="number" id="paid_bandwidth" name="paid_bandwidth"
        min="<?= $min_bandwidth ?>" max="<?= $max_bandwidth ?>">
<br>

        <label for="gps_north">What is your north GPS coordinate?</label>
        <input required type="number" name="gps_north" id="gps_north"
        min="<?= $min_north ?>" max="<?= $max_north ?>">
<br>

        <label for="gps_east">What is your east GPS coordinate?</label>
        <input required type="number" name="gps_east" id="gps_east"
        min="<?= $min_east ?>" max="<?= $max_east ?>">
<br>

        <button id="begin-button" type="button">Begin Collection</button>
    </fieldset>
</form>
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
     *
     */
    ready(function go() {
        var begin_button = document.getElementById('begin-button');
        var isp_name_input = document.getElementById('isp_name');
        var paid_bandwidth_input = document.getElementById('paid_bandwidth');
        var gps_north_input = document.getElementById('gps_north');
        var gps_east_input = document.getElementById('gps_east');
        var download_speed = undefined;

        /*
         *
         */
        var validateInputs = function() {
            return (isp_name_input.value != 'none'
                && gps_north_input.value != ''
                && gps_east_input.value != '');
        };

        /*
         *
         */
        var measureSpeed = function() {

        };

        /*
         *
         */
        var sendReport = function() {
            //TODO: Remove
            download_speed = 1;
            if (download_speed == undefined) {
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
                alert("Part of the form in incomplete. Could you doublecheck that you've "
                    + "selected an ISP and your GPS coordinates and then try again?");
                return;
            }
            measureSpeed();
            sendReport();
            var intervalID = window.setInterval(function handleData() {
                console.log("run");
                measureSpeed();
                sendReport();
                
            }, 60000);
        };

        begin_button.addEventListener('click', beginReporting, false);
    });

</script>
</body>
</html>
