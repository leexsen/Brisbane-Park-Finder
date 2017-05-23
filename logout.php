<?php
    // Remove the user's session and return the user to the index page
    session_start();
    unset($_SESSION['user']);
    header('Location: index.php');
    exit();
    ?>
