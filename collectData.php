<?php
/**
 * Make sure this is a legitimate response to a post request
 */
if (! (($_SERVER['REQUEST_METHOD'] == 'POST')
    && !empty($_POST['isp_name'])
    && !empty($_POST['gps_north'])
    && !empty($_POST['gps_east'])
    && !empty($_POST['download_time'])
    && !empty($_POST['timestamp'])
    )) {
    echo 'Wrong request type or missing parameters.';
} else {
    //pull in configuration
    require('isp-options.php');

    //set up variables to hold request values
    $isp_name = $_POST['isp_name'];
    $gps_north = $_POST['gps_north'];
    $gps_east = $_POST['gps_east'];
    $download_time = $_POST['download_time'];
    $timestamp = $_POST['timestamp'];
    if (!empty($_POST['paid_bandwidth'])) {  
        $paid_bandwidth = $_POST['paid_bandwidth'];
        $paid_bandwidth= filter_var($paid_bandwidth, FILTER_VALIDATE_FLOAT, [
            'options' => [
                'min_range' => $min_bandwidth,
                'max_range' => $max_bandwidth,
            ],
        ]);
    } else {
        $paid_bandwidth = 'NA';
    }

    //validate request data
    $gps_north = filter_var($gps_north, FILTER_VALIDATE_FLOAT, [
            'options' => [
                'min_range' => $min_north,
                'max_range' => $max_north,
            ],
    ]);
    $gps_east = filter_var($gps_east, FILTER_VALIDATE_FLOAT, [
            'options' => [
                'min_range' => $min_east,
                'max_range' => $max_east,
            ],
    ]);
    $download_time = filter_var($download_time, FILTER_VALIDATE_FLOAT, [
            'options' => [
                'min_range' => $min_bandwidth,
                'max_range' => $max_bandwidth,
            ],
    ]);
    $timestamp = filter_var($timestamp, FILTER_VALIDATE_INT);

    //ensure ISP selection is valid
    if(! (in_array($isp_name, $isp_options)
        && $gps_north
        && $gps_east
        && $download_time
        && $timestamp
        && $paid_bandwidth
    )) {
        echo 'Invalid data.';
        die();
    }

    //Ensure correct date timezones
    if(! date_default_timezone_set($default_timezone)) {
        echo 'Server timezone setting is invalid.';
    }

    $filename = date('l\-F\-d\-Y').'.csv';
    if (! file_exists('./'.$filename)) {
        //no log file for today
        if (! file_put_contents($filename, "ISP, GPSN, GPSE, DownloadSpeed, Timestamp, Bandwidth\n")) {
            echo "File creation error.";
        }
    }

    if (! file_put_contents($filename,
        "$isp_name, $gps_north, $gps_east, $download_time, $timestamp, $paid_bandwidth\n",
        FILE_APPEND)) {
        echo "File update error.";
    }
    echo "$isp_name, $gps_north, $gps_east, $download_time, $timestamp, $paid_bandwidth\n";
}
