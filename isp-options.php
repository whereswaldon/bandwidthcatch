<?php
$isp_options = [
    'Charter',
    'Campus',
    'AT&T',
    'Skybest',
    'Other',
];

$max_north = 1000;
$min_north = 0;
$max_east = 1000; 
$min_east = 0;

$min_bandwidth = 0;
$max_bandwidth = 1000;

$default_timezone = 'America/New_York';

$base_url = 'http://7fc0d5d1.ngrok.io/';
$post_request_target = $base_url.'collectData.php';
$image_name = 'test.jpg';
$image_path = $base_url.$image_name;

$image_size_bytes = 4742956; //this is in bytes
$image_size = $image_size_bytes * 8; //this is in bits
