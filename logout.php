<?php
    session_start();

    if(isset($_SESSION['username'])) {
        session_destroy();
    } else {

    }
    header('location:index.php');
    exit();
?>