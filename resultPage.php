<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Search Results - Brisbane Park Finder</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiQBcLHFTpkoWsFJgGwtDWQ52GH-KFS-w&callback=initMap"></script>
    </head>

    <body>
        <?php
			require_once 'header.php';

			if (isset($_GET['typeSelector']) && isset($_GET['value'])) {
				$type = $_GET['typeSelector'];
				$value = urldecode($_GET['value']);

			} else {
				$type = "";
				$value = "";
			}

			searchParks($type, $value);
		?>

        <div id="mapWrapper">
            <div id="map"></div>
        </div>

        <div id="footer">
            <span id="footerText">Made by Sean Li and Daniel Paten</span>
        </div>
    </body>
</html>
