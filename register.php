<?php
    require_once 'functions.php';
    // Check if the user is accessing the page when already logged in
    if (!isset($_SESSION['user'])) {
        // Only validate if all fields are set
        if (isset($_POST['fNameForm']) && isset($_POST['lNameForm']) && isset($_POST['emailForm']) && isset($_POST['passwordForm']) && isset($_POST['confirmForm']) && isset($_POST['dateForm'])) {
            // Perform the validation
            require_once 'formValidation.php';
            $name = checkName($_POST['fNameForm'], $_POST['lNameForm']);
            $email = checkEmail($_POST['emailForm']);
            $password = checkPassword($_POST['passwordForm']);
            $confirm = checkConfirm($_POST['passwordForm'], $_POST['confirmForm']);
            $date = checkDay($_POST['dateForm']);
            $usage = checkRegister($_POST['emailForm']);
            
            if (!$name || !$email || !$password || !$confirm || !$date || !$usage) {
                // redisplay the form
                include 'registerForm.php';
            } else {
                // Add the new user to the database
                addUser($_POST['fNameForm'], $_POST['lNameForm'], $_POST['emailForm'], $_POST['passwordForm'], $_POST['dateForm']);
                
                // Set the session value to the user's id
                $uid = getUserID($_POST['emailForm']);
                $_SESSION['user'] = $uid;
                header('Location: index.php');
                exit();
            }
        } else {
            include 'registerForm.php';
        }
    } else {
        // Redirect the user to the index page if they are already logged in
        header('Location: index.php');
        exit();
    }
    ?>
