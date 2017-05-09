<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>NAME OF ITEM - Brisbane Park Finder</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiQBcLHFTpkoWsFJgGwtDWQ52GH-KFS-w&callback=initMap"></script>
    </head>

    <body>
		<?php require_once 'header.php' ?>;
        
        <div id="detailedInfo">
            <div class="contentCell">
                <span class="contentTitle">7TH BRIGADE PARK</span>
                <div class="contentRating">
                    <img src="imgs/filledStar.svg" alt="stars">
                    <img src="imgs/filledStar.svg" alt="stars">
                    <img src="imgs/filledStar.svg" alt="stars">
                </div>
                <span class="contentDescription">HAMILTON RD, CHERMSIDE</span>
                <data value="-27.38006149,153.0387005"></data>
            </div>

            <div id="commentList">
                <div class="commentCell">
                    <span class="commentTitle">Leonhard Euler</span>
                    <div class="commentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="comment">This is a beautiful park, I love it</span>
                </div>

                <div class="commentCell">
                    <span class="commentTitle">Isaac Newton</span>
                    <div class="commentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="comment">This is where I got hit by an apple</span>
                </div>

                <div class="commentCell">
                    <span class="commentTitle">Alan Turing</span>
                    <div class="commentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="comment">It's good generally but it's a little bit small</span>
                </div>

                <div class="commentCell">
                    <span class="commentTitle">John von Neumann</span>
                    <div class="commentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="comment">It's nice and quiet</span>
                </div>

                <div class="commentCell">
                    <span class="commentTitle">Ada Lovelace</span>
                    <div class="commentRating">
                        <img src="imgs/filledStar.svg" alt="stars">
                        <img src="imgs/filledStar.svg" alt="stars">
                    </div>
                    <span class="comment">Not too bad</span>
                </div>
            </div>

            <div id="commentBox">
                <form action="itemPage.html" method="post">
                    <textarea id="commentArea" name="comment" placeholder="Leave a comment"></textarea>
                    <div class="commentRating">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 1)">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 2)">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 3)">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 4)">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 5)">
                    </div>
                    <input type="submit" id="commentSubmitButton" value="Submit">
                </form>
            </div>
        </div>

        <div id="mapWrapper">
            <div id="map"></div>
        </div>

        <div id="footer">
            <span id="footerText">Made by Sean Li and Daniel Paten</span>
        </div>
    </body>
</html>
