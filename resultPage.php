<?php require_once 'functions.php';?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Search Results - Brisbane Park Finder</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <?php
			require_once 'header.php';
            
            // Set the arguments for the search parks function
			if (isset($_GET['submit'])) {
				$type = $_GET['typeSelector'];
				$value = urldecode($_GET['value']);

			} else {
				$type = "";
				$value = "";
			}
			
            // Gte the results of the search
			echo '<div id="contentList">';
				searchParks($type, $value);
			echo '</div>';
		?>

        <div id="mapWrapper">
            <div id="map"></div>
        </div>

        <div id="footer">
            <span id="footerText">Made by Sean Li and Daniel Paten</span>
        </div>
    </body>

<script src="script.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiQBcLHFTpkoWsFJgGwtDWQ52GH-KFS-w&callback=initMap"></script>

</html>
