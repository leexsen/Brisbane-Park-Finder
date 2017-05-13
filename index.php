<!DOCTYPE html>
<?php require_once 'functions.php'; ?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Search - Brisbane Park Finder</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>
    </head>
    <body>
        <div id="header">
            <div id="logo">
                <span id="text_brisbane">Brisbane</span>
                <span id="text_park">Park</span>
                <span id="text_finder">Finder</span>
            </div>
            <div id="loginLink">
                <a href="login.php">Login</a>
            </div>
            <div id="navigationBar">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="resultPage.php">All Parks</a></li>
                    <li><a href="itemPage.php">Random Park</a></li>
                </ul>
            </div>
        </div>
        <div id="whiteBox">
            <span id="titleText">Search By</span><br>
            <form action="resultPage.php" method="get" onsubmit="return searchSubmit(this)">
                <select id="typeSelector" class="typeSelector_name" name="typeSelector" onchange="typeSelectorChanged()">
                    <option value="name">Name</option>
                    <option value="suburb">Suburb</option>
                    <option value="rating">Rating</option>
                    <option value="location">Location</option>
                </select>
                <br>
                <div id="ratingSelector" class="ratingSelector_name">
                    <img src="imgs/unfilledStar.svg" alt="star" onclick="ratingStarClicked(this, 1)">
                    <img src="imgs/unfilledStar.svg" alt="star" onclick="ratingStarClicked(this, 2)">
                    <img src="imgs/unfilledStar.svg" alt="star" onclick="ratingStarClicked(this, 3)">
                    <img src="imgs/unfilledStar.svg" alt="star" onclick="ratingStarClicked(this, 4)">
                    <img src="imgs/unfilledStar.svg" alt="star" onclick="ratingStarClicked(this, 5)">
                </div>
                <select id="suburbSelector" class="suburbSelector_name">
				<?php
					$db = connectDB();
					showSuburbOptions($db);
					disconnectDB($db);
				?>
                </select>
                <input type="text" id="searchBar" class="searchBar_name" name="value" placeholder="Search parks">
                <input type="submit" id="submitButton" value="Search">
            </form>
        </div>
        <div id="footer">
            <span id="footerText">Made by Sean Li and Daniel Paten</span>
        </div>
    </body>
</html>
