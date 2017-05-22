<?php
    session_start();
    if (!isset($_SESSION['isLoggedIn'])) {
        if (isset($_POST['fNameForm']) || isset($_POST['lNameForm']) || isset($_POST['emailForm']) || isset($_POST['passwordForm']) || isset($_POST['confirmForm']) || isset($_POST['dateForm'])) {
            require_once 'formValidation.php';
            $name = checkName($_POST, 'fNameForm', 'lNameForm');
            $email = checkEmail($_POST, 'emailForm');
            $password = checkPassword($_POST, 'passwordForm');
            $confirm = checkConfirm($_POST, 'passwordForm', 'confirmForm');
            $date = checkDay($_POST, 'dateForm');
            $usage = checkRegister($_POST, 'emailForm');
            
            if (!$name || !$email || !$password || !$confirm || !$date || !$usage) {
                // redisplay the form
                include 'registerForm.php';
            } else {
                addUser($_POST['fNameForm'], $_POST['lNameForm'], $_POST['emailForm'], $_POST['passwordForm'], $_POST['dateForm']);
                session_start();
                $_SESSION['isLoggedIn'] = true;
                header('Location: index.php');
                exit();
            }
        } else {
            include 'registerForm.php';
        }
    } else {
        header('Location: index.php');
        exit();
    }
    ?>
