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
			require_once 'functions.php';
			$db = connectDB();
			require 'header.php';

			if (isset($_GET['typeSelector']) && isset($_GET['value'])) {
				$type = $_GET['typeSelector'];
				$value = urldecode($_GET['value']);

			} else {
				
			}

			searchParks($db, $type, $value);
		?>
<!--
        <div id="contentList">
            <a href="itemPage.html">
                <div class="contentCell">
                    <span class="contentTitle">7TH BRIGADE PARK</span>
                    <div class="contentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="contentDescription">HAMILTON RD, CHERMSIDE</span>
                    <data value="-27.38006149,153.0387005"></data>
                </div>
            </a>

            <a href="itemPage.html">
                <div class="contentCell">
                    <span class="contentTitle">A. J. JONES RECREATION RESERVE</span>
                    <div class="contentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="contentDescription">CORNWALL ST, GREENSLOPES</span>
                    <data value="-27.50346312,153.0435636"></data>
                </div>
            </a>

            <a href="itemPage.html">
                <div class="contentCell">
                    <span class="contentTitle">A.R.C.HILL PARK</span>
                    <div class="contentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="contentDescription">GOSS RD, VIRGINIA</span>
                    <data value="-27.38441077,153.0558459"></data>
                </div>
            </a>

            <a href="itemPage.html">
                <div class="contentCell">
                    <span class="contentTitle">ABBEVILLE STREET PARK</span>
                    <div class="contentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="contentDescription">ABBEVILLE ST, UPR MT GRAVATT</span>
                    <data value="-27.54451364,153.0889922"></data>
                </div>
            </a>

            <a href="itemPage.html">
                <div class="contentCell">
                    <span class="contentTitle">ABBOTT STREET PARK</span>
                    <div class="contentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="contentDescription">ABBOTT ST, CAMP HILL</span>
                    <data value="-27.49972065,153.0858355"></data>
                </div>
            </a>

            <a href="itemPage.html">
                <div class="contentCell">
                    <span class="contentTitle">ABLINGTON WAY PARK</span>
                    <div class="contentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="contentDescription">ABLINGTON WAY, CARINDALE</span>
                    <data value="-27.52201163,153.1204848"></data>
                </div>
            </a>

            <a href="itemPage.html">
                <div class="contentCell">
                    <span class="contentTitle">ACACIA FOREST PARK</span>
                    <div class="contentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="contentDescription">ACACIA RD, KARAWATHA</span>
                    <data value="-27.62418129,153.0960688"></data>
                </div>
            </a>

            <a href="itemPage.html">
                <div class="contentCell">
                    <span class="contentTitle">ACACIA PARK</span>
                    <div class="contentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="contentDescription">CARMODY RD, ST LUCIA</span>
                    <data value="-27.4991659,153.0079571"></data>
                </div>
            </a>
        </div>
		-->

        <div id="mapWrapper">
            <div id="map"></div>
        </div>

        <div id="footer">
            <span id="footerText">Made by Sean Li and Daniel Paten</span>
        </div>
    </body>
</html>
