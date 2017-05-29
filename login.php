<?php
    require_once 'functions.php';
    // Check if the user is accessing the page when already logged in
    if (!isset($_SESSION['user'])) {
        // Only validate if all the fields are set
        if (isset($_POST['emailForm']) && isset($_POST['passwordForm'])) {
            
            // Perform the validation
            require_once 'formValidation.php';
            $email = checkEmail($_POST['emailForm']);
            $password = checkPassword($_POST['passwordForm']);
            $data = checkLogin($_POST['emailForm'], $_POST['passwordForm']);
            $db = connectDB();
            if (!$email || !$password || !$data) {
                // redisplay the form
                include 'loginForm.php';
            } else {
                // Set the session value to the user's id
                $uid = getUserID($_POST['emailForm']);
                $_SESSION['user'] = $uid;
                header('Location: index.php');
                exit();
            }
        } else {
            include 'loginForm.php';
        }
    } else {
        // Redirect the user to the index page if they are already logged in
        header('Location: index.php');
        exit();
    }
    ?>
