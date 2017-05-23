<?php
    require_once 'functions.php';
    
    // Checks if the name fields are empty and returns false if so
    function checkName($fname, $lname) {
        if (empty($fname) || empty($lname)) {
            echo '<style type="text/css"> #noName {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
        
    }
    
    // Checks if the email field is empty or invalid and returns false if so
    function checkEmail($email) {
        // Pattern ensures there is at least one character surrounding the '@' and '.'
        // e.g. 'g@g.c' is acceptable
        $emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
        if (empty($email) || !preg_match($emailPattern, $email)) {
            echo '<style type="text/css"> #noEmail {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
    }
    
    // Checks if the password field is empty and returns false if so
    function checkPassword($password) {
        if (empty($password)) {
            echo '<style type="text/css"> #noPassword {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
    }
    
    // Checks if the confirm password field is empty or if the password fields do not match and returns false if so
    function checkConfirm($password, $confirm) {
        if (empty($confirm)) {
            echo '<style type="text/css"> #noConfirm {display: inline-block;} </style>';
            return false;
        } else if ($password != $confirm) {
            echo '<style type="text/css"> #badConfirm {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
    }
    
    // Checks if the date field is empty or invalid and returns false if so
    function checkDay($date) {
        // Pattern matches the style that is entered into the database
        $datePattern = '/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/';
        if (empty($date) || !preg_match($datePattern, $date)) {
            echo '<style type="text/css"> #noDate {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
    }
    
    // Checks if the login details are in the server and returns true if so, otherwise returns false
    function checkLogin($email, $password) {
        
        $sql = 'select * from members where email = :email and password = SHA2(CONCAT(:password, salt), 0)';
        
        $db = connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            echo '<style type="text/css"> #incorrectLogin {display: inline-block;} </style>';
            disconnectDB($db);
            return false;
        } else {
            disconnectDB($db);
            return true;
        }
    }
    
    // Checks if the email provided is already in the database, and returns false if so
    function checkRegister($email) {
        
        $sql = 'select * from members where email = :email';
        
        $db = connectDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo '<style type="text/css"> #incorrectLogin {display: inline-block;} </style>';
            disconnectDB($db);
            return false;
        } else {
            return true;
        }
    }
    
    ?>
