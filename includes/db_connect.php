<?php
    define("CAN_REGISTER", "any");
    define("DEFAULT_ROLE", "member");

    define("SECURE", FALSE); //For dev environment

    $mysqli = new mysqli("localhost", "apache", "fb8bda50b9f86b229be154a8e32edfc7", "Dashboard");

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
?>