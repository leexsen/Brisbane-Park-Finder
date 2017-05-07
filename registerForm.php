<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Register - Brisbane Park Finder</title>
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
            <div id="searchBox">
                <form action="resultPage.html" method="get" onsubmit="return searchSubmit(this)">
                    <select id="typeSelectorHeader" class="typeSelector_name" name="typeSelector" onchange="typeSelectorChanged()">
                        <option value="name">Name</option>
                        <option value="suburb">Suburb</option>
                        <option value="rating">Rating</option>
                        <option value="location">Location</option>
                    </select>
                    <div id="ratingSelectorHeader" class="ratingSelector_name">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 1)">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 2)">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 3)">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 4)">
                        <img src="imgs/unfilledStar.svg" alt="stars" onclick="ratingStarClicked(this, 5)">
                    </div>
                    <select id="suburbSelectorHeader" class="suburbSelector_name">
                        <option value="suburb1">Suburb 1</option>
                        <option value="suburb2">Suburb 2</option>
                        <option value="suburb3">Suburb 3</option>
                        <option value="suburb4">Suburb 4</option>
                        <option value="suburb5">Suburb 5</option>
                        <option value="suburb6">Suburb 6</option>
                        <option value="suburb7">Suburb 7</option>
                        <option value="suburb8">Suburb 8</option>
                        <option value="suburb9">Suburb 9</option>
                    </select>
                    <input type="text" id="searchBarHeader" class="searchBar_name" name="value" placeholder="Search parks">
                    <input type="submit" id="submitButtonHeader" value="Search">
                </form>
            </div>
            <div id="navigationBar">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="resultPage.html">All Parks</a></li>
                    <li><a href="itemPage.html">Random Park</a></li>
                </ul>
            </div>
        </div>
        <div id="whiteBox">
            <span id="titleText">Register</span><br><br>
            <span id="incorrectLogin" class="errorText">This email is already in use. Please use another email.</span><br>
            <form method="post" action="register.php">
                <input type="text" class="loginFormHalf" name="fNameForm" placeholder="First Name" onkeypress="clearError('noName')" value="<?php
                    if(isset($_POST['fNameForm'])) {
                        echo htmlspecialchars($_POST['fNameForm']);
                    }
                    ?>"/>
                <input type="text" class="loginFormHalf" name="lNameForm" placeholder="Last Name" onkeypress="clearError('noName')" value="<?php
                    if(isset($_POST['lNameForm'])) {
                        echo htmlspecialchars($_POST['lNameForm']);
                    }
                    ?>"/><br>
                <span id="noName" class="errorText">Please enter a name.</span><br>
                <input type="text" class="loginForm" name="emailForm" placeholder="Email" onkeypress="clearError('noEmail')" value="<?php
                    if(isset($_POST['emailForm'])) {
                        echo htmlspecialchars($_POST['emailForm']);
                    }
                    ?>"/><br>
                <span id="noEmail" class="errorText">Please enter a valid email address.</span><br>
                <input type="date" class="loginForm" name="dateForm" placeholder="Birthday (yyyy-mm-dd)" onchange="clearError('noDate')" value="<?php
                    if(isset($_POST['dateForm'])) {
                        echo htmlspecialchars($_POST['dateForm']);
                    }
                    ?>"/><br>
                <span id="noDate" class="errorText">Please provide your birth date.</span><br>
                <input type="password" class="loginForm" name="passwordForm" placeholder="Password" onkeypress="clearError('noPassword')" value="<?php
                    if(isset($_POST['passwordForm'])) {
                        echo htmlspecialchars($_POST['passwordForm']);
                    }
                    ?>"/><br>
                <span id="noPassword" class="errorText">Please enter a password.</span><br>
                <input type="password" class="loginForm" name="confirmForm" placeholder="Confirm Password" onkeypress="clearError('noConfirm')" value="<?php
                    if(isset($_POST['confirmForm'])) {
                        echo htmlspecialchars($_POST['confirmForm']);
                    }
                    ?>"/><br>
                <span id="noConfirm" class="errorText">Please confirm your password.</span>
                <span id="badConfirm" class="errorText">Please ensure the password fields match.</span><br>
                <input type="submit" id="loginButton" value="Register">
                <span id="loginOrRegisterText"> or <a href="login.php">Login</a></span>
            </form>
        </div>
        <div id="footer">
            <span id="footerText">Made by Sean Li and Daniel Paten</span>
        </div>
    </body>
</html>