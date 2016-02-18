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
            <option>Select a provider</option>
        <?php
            foreach($isp_options as $option) {
                echo "<option value=\"$option\">$option</option>";
            }
        ?>
        </select>
<br>
        
        <label for="paid_bandwidth">How many Megabits per second are you paying for? (leave blank if unknown)</label>
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

        <button>Begin Collection</button>
    </fieldset>
</form>
<script>

</script>
</body>
</html>
