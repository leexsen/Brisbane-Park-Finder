<?php
    require_once 'functions.php';
    // Checks if the name fields are empty and returns false if so
    function checkName($form, $fname, $lname) {
        if (empty($form[$fname]) || empty($form[$lname])) {
            echo '<style type="text/css"> #noName {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
        
    }
    
    // Checks if the email field is empty or invalid and returns false if so
    function checkEmail($form, $name) {
        $emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
        if (empty($form[$name]) || !preg_match($emailPattern, $form[$name])) {
            echo '<style type="text/css"> #noEmail {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
    }
    
    // Checks if the password field is empty and returns false if so
    function checkPassword($form, $name) {
        if (empty($form[$name])) {
            echo '<style type="text/css"> #noPassword {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
    }
    
    // Checks if the confirm password field is empty or if the password fields do not match and returns false if so
    function checkConfirm($form, $password, $confirm) {
        if (empty($form[$confirm])) {
            echo '<style type="text/css"> #noConfirm {display: inline-block;} </style>';
            return false;
        } else if ($form[$password] != $form[$confirm]) {
            echo '<style type="text/css"> #badConfirm {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
    }
    
    // Checks if the date field is empty or invalid and returns false if so
    function checkDay($form, $name) {
        $datePattern = '/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/';
        if (empty($form[$name]) || !preg_match($datePattern, $form[$name])) {
            echo '<style type="text/css"> #noDate {display: inline-block;} </style>';
            return false;
        } else {
            return true;
        }
    }
    
    // Checks if the login details are in the server and returns true if so, otherwise returns false
    function checkLogin($form, $email, $password) {
        
        $sql = 'select email, password from users';
        
        $db = connectDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        
        $correctLogin = false;
        
        foreach ($results as $row) {

            if ($form[$email] == $row['email'] && $form[$password] == $row['password']) {
                $correctLogin = true;
                break;
            }
        }
        
        if (!$correctLogin) {
            echo '<style type="text/css"> #incorrectLogin {display: inline-block;} </style>';
            disconnectDB($db);
            return false;
        } else {
            disconnectDB($db);
            return true;
        }
    }
    
    // Checks if the email provided is already in the database, and returns false if so
    function checkRegister($form, $email) {
        
        $sql = 'select email from users';
        
        $db = connectDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        
        $emailTaken = false;
        
        foreach ($results as $row) {
            if ($form[$email] == $row['email']) {
                $emailTaken = true;
                break;
            }
        }
        
        if ($emailTaken) {
            echo '<style type="text/css"> #incorrectLogin {display: inline-block;} </style>';
            disconnectDB($db);
            return false;
        } else {
            return true;
        }
    }
    
    ?>
