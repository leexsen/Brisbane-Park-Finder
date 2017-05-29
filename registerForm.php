<?php require_once 'functions.php';?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Register - Brisbane Park Finder</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>
    </head>
    <body>
		<?php require_once 'header.php'; ?>

        <div id="whiteBox">
            <span id="titleText">Register</span><br><br>
            <span id="incorrectLogin" class="errorText">This email is already in use. Please use another email.</span><br>
            <form method="post" action="register.php" onsubmit="return registerSubmit(this);">
                <input type="text" class="loginFormHalf" name="fNameForm" placeholder="First Name" onkeypress="clearError('noName')" value="<?php
                    // Redisplay the input if the form didn't submit
                    if(isset($_POST['fNameForm'])) {
                        echo htmlspecialchars($_POST['fNameForm']);
                    }
                    ?>"/>
                <input type="text" class="loginFormHalf" name="lNameForm" placeholder="Last Name" onkeypress="clearError('noName')" value="<?php
                    // Redisplay the input if the form didn't submit
                    if(isset($_POST['lNameForm'])) {
                        echo htmlspecialchars($_POST['lNameForm']);
                    }
                    ?>"/><br>
                <span id="noName" class="errorText">Please enter a name.</span><br>
                <input type="text" class="loginForm" name="emailForm" placeholder="Email" onkeypress="clearError('noEmail')" value="<?php
                    // Redisplay the input if the form didn't submit
                    if(isset($_POST['emailForm'])) {
                        echo htmlspecialchars($_POST['emailForm']);
                    }
                    ?>"/><br>
                <span id="noEmail" class="errorText">Please enter a valid email address.</span><br>
                <input type="date" class="loginForm" name="dateForm" placeholder="Birthday (yyyy-mm-dd)" onchange="clearError('noDate')" value="<?php
                    // Redisplay the input if the form didn't submit
                    if(isset($_POST['dateForm'])) {
                        echo htmlspecialchars($_POST['dateForm']);
                    }
                    ?>"/><br>
                <span id="noDate" class="errorText">Please provide your birth date.</span><br>
                <input type="password" class="loginForm" name="passwordForm" placeholder="Password" onkeypress="clearError('noPassword')" value="<?php
                    // Redisplay the input if the form didn't submit
                    if(isset($_POST['passwordForm'])) {
                        echo htmlspecialchars($_POST['passwordForm']);
                    }
                    ?>"/><br>
                <span id="noPassword" class="errorText">Please enter a password.</span><br>
                <input type="password" class="loginForm" name="confirmForm" placeholder="Confirm Password" onkeypress="clearError('noConfirm')" value="<?php
                    // Redisplay the input if the form didn't submit
                    if(isset($_POST['confirmForm'])) {
                        echo htmlspecialchars($_POST['confirmForm']);
                    }
                    ?>"/><br>
                <span id="noConfirm" class="errorText">Please confirm your password.</span>
                <span id="badConfirm" class="errorText">Please ensure the password fields match.</span><br>
                <input type="submit" id="loginButton" value="Register">
                <span id="loginOrRegisterText"> or <a href="login.php">Log in</a></span>
            </form>
        </div>
        <div id="footer">
            <span id="footerText">Made by Sean Li and Daniel Paten</span>
        </div>
    </body>
</html>
