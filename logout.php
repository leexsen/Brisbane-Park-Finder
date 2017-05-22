<?php
    session_start();
    unset($_SESSION['isLoggedIn']);
    header('Location: index.php');
    exit();
    ?>
