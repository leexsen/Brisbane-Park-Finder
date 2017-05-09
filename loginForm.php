<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login - Brisbane Park Finder</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>
    </head>
    <body>
		<?php require_once 'header.php'; ?>

        <div id="whiteBox">
            <span id="titleText">Login</span><br><br>
            <span id="incorrectLogin" class="errorText">Incorrect login. Please try again.</span><br>
            <form method="post" action="login.php">
                <input type="text" id="loginFormTop" name="emailForm" placeholder="Email" onkeypress="clearError('noEmail')" value="<?php
                        if(isset($_POST['emailForm'])) {
                            echo htmlspecialchars($_POST['emailForm']);
                        }
                    ?>"/><br>
                <span id="noEmail" class="errorText">Please enter a valid email address.</span><br>
                <input type="password" class="loginForm" name="passwordForm" placeholder="Password" onkeypress="clearError('noPassword')" value="<?php
                        if(isset($_POST['passwordForm'])) {
                            echo htmlspecialchars($_POST['passwordForm']);
                        }
                    ?>"/><br>
                <span id="noPassword" class="errorText">Please enter in a password.</span><br>
                <input type="submit" id="loginButton" value="Login">
                <span id="loginOrRegisterText"> or <a href="register.php">Register</a></span>
            </form>
        </div>
        <div id="footer">
            <span id="footerText">Made by Sean Li and Daniel Paten</span>
        </div>
    </body>
</html>
