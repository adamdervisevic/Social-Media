<?php
    mysqli_report(MYSQLI_REPORT_OFF);
    $server = "localhost";
    $database = "network";
    $username = "adam";
    $password = "adam123"; 

    $conn = new mysqli($server, $username, $password, $database); 
    if ($conn->connect_error)
    {
        die("Neuspela konekcija: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
?>