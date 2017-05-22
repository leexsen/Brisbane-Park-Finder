<?php
    require_once 'functions.php';
    if (!isset($_SESSION['isLoggedIn'])) {
        if (isset($_POST['emailForm']) || isset($_POST['passwordForm'])) {
            require_once 'formValidation.php';
            $email = checkEmail($_POST, 'emailForm');
            $password = checkPassword($_POST, 'passwordForm');
            $data = checkLogin($_POST, 'emailForm', 'passwordForm');
            $db = connectDB();
            if (!$email || !$password || !$data) {
                // redisplay the form
                include 'loginForm.php';
            } else {
                $_SESSION['isLoggedIn'] = true;
                header('Location: index.php');
                exit();
            }
        } else {
            include 'loginForm.php';
        }
    } else {
        header('Location: index.php');
        exit();
    }
    ?>
