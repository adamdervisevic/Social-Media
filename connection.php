<?php
    mysqli_report(MYSQLI_REPORT_OFF);
    $server = "localhost";
    $database = "network";
    $username = "adam";
    $password = "adam123"; // promeniti username i password za domaci

    $conn = new mysqli($server, $username, $password, $database); // ovde moze u nekim verzijama PHP-a da dodje do greske (ukoliko nije uspostavljena konekcija), i da uopste ne mozemo da dodjemo do 10. linije koda
    // zato smo pokrenuli prvo komandu iz druge linije koda (da ne bi doslo to toga)
    if ($conn->connect_error)
    {
        die("Neuspela konekcija: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
?>