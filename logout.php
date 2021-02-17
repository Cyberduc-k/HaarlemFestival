<?php
    //Remove session to log user out
    if(!isset($_SESSION)) session_start();
        session_destroy();

    //Make cookies expire on page reset
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
    }

    //Go back to login
    header("Location: index.php");
    ?>