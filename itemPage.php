<?php require_once 'functions.php';?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php
            require_once 'header.php';
            
            date_default_timezone_set("Australia/Brisbane");
            
            if (isset($_GET['pid'])) {
                $pid = $_GET['pid'];
            } else if (isset($_POST['pid'])) {
                $pid = $_POST['pid'];
            } else {
				$pid = rand(1, 2214);
			}
            
            getParkName($pid) ?>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
		<?php

			if (isset($_POST['comment'])) {
                $uid = $_SESSION['user'];
                $pid = $_POST['pid'];
				$comment = $_POST['comment'];
				$rating = $_POST['rating'];
				$date = date('Y-m-d');

				uploadComment($uid, $pid, $comment, $rating, $date);
			}
		?>
        
        <div id="detailedInfo" itemscope itemtype="http://schema.org/Review">
			<?php
				searchParks('pid', $pid);
				searchComments($pid);
			?>

            <div id="commentBox">
                <?php
                    if (isset($_SESSION['user'])) {
                        echo "<form action='itemPage.php' method='post' onsubmit='return commentSubmit(this)'>
                            <textarea id='commentArea' name='comment' placeholder='Leave a comment'></textarea>
                            <div class='commentRating' id='commentBoxRating'>
                            <img src='imgs/unfilledStar.svg' alt='stars' onclick='ratingStarClicked(this, 1)'>
                            <img src='imgs/unfilledStar.svg' alt='stars' onclick='ratingStarClicked(this, 2)'>
                            <img src='imgs/unfilledStar.svg' alt='stars' onclick='ratingStarClicked(this, 3)'>
                            <img src='imgs/unfilledStar.svg' alt='stars' onclick='ratingStarClicked(this, 4)'>
                            <img src='imgs/unfilledStar.svg' alt='stars' onclick='ratingStarClicked(this, 5)'>
                            </div>
                            <input type='hidden' name='rating' class='rating'>
                            <input type='hidden' name='pid' value='$pid'>
                            <input type='submit' id='commentSubmitButton' value='Submit'>
                        </form>";
                    } else {
                        echo '<br><span class="noResultsText">You must be logged in to enter a comment.</span>';
                    }
                    ?>
            </div>
        </div>

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
