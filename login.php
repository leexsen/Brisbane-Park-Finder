<?php
    if (isset($_POST['emailForm']) || isset($_POST['passwordForm'])) {
        require_once 'formValidation.php';
        $email = checkEmail($_POST, 'emailForm');
        $password = checkPassword($_POST, 'passwordForm');
        $data = checkLogin($_POST, 'emailForm', 'passwordForm');

        if (!$email || !$password || !$data) {
            // redisplay the form
            include 'loginForm.php';
        } else {
            echo '<script type="text/javascript">window.location.href = \'index.php\';</script>';
        }
    } else {
        include 'loginForm.php';
    }
    ?>
