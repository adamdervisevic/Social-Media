<?php
    session_start();
    if(empty($_SESSION["id"]))
    {
        header("Location: index.php");
    }
    $id = $_SESSION["id"];
    require_once "connection.php";
    require_once "header.php";
    require_once "validation.php";

    if(isset($_GET["friend_id"])) {
        //zahtev za pracenje drugog korisnika
        $friendId = $conn->real_escape_string($_GET["friend_id"]);

        $q = "SELECT * FROM `followers` 
                WHERE `id_sender` = $id
                AND `id_receiver` = $friendId";
        $result = $conn->query($q);
        if($result->num_rows == 0)
        {
            $upit = "INSERT INTO `followers`(`id_sender`, `id_receiver`)
                    VALUE ($id, $friendId)";
            $result1 = $conn->query($upit);
        }
    }

    if(isset($_GET["unfriend_id"])) {
        //Zahtev da se drugi korisnik otprati
        $friendId = $conn->real_escape_string($_GET["unfriend_id"]);

        $q = "DELETE FROM `followers`
                WHERE `id_sender` = $id
                AND `id_receiver` = $friendId";

        $conn->query($q);
    }
    
    $query = "SELECT * FROM `followers`";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $avatar = $row["avatar"];
        $gender = getGender($row["id_receiver"], $conn);

        if (empty($avatar) || $avatar == "default") {
            if ($gender == "m") {
                $avatar = "images/male_avatar.png";
            } elseif ($gender == "f") {
                $avatar = "images/female_avatar.png";
            } else {
                $avatar = "images/other_avatar.png";
            }
        }

        echo "<div>";
        echo "<img src='$avatar' alt='Avatar'>";
        echo "</div>";
    }

    //Odredimo koje druge korisnike prati logovan korisnik
    $upit1 = "SELECT `id_receiver` FROM `followers` WHERE `id_sender` = $id";
    $res1 = $conn->query($upit1);
    $niz1 = array();
    while($row = $res1->fetch_array(MYSQLI_NUM)) {
        $niz1[] = $row[0];
    }
    //Odrediti koji drugi korisnici prate logovanog korisnika
    $upit2 = "SELECT `id_sender` FROM `followers` WHERE `id_receiver` = $id";
    $res2 = $conn->query($upit2);
    $niz2 = array();
    while($row = $res2->fetch_array(MYSQLI_NUM)) {
        $niz2[] = $row[0];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members of Social Network</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>See other members from our site</h1>
    <?php
        $q = "SELECT `u`.`id`, `u`.`username`, 
                CONCAT(`p`.`first_name`, ' ', `p`.`last_name`) AS `full_name`
                FROM `users` AS `u`
                LEFT JOIN `profiles` AS `p`
                ON `u`.`id` = `p`.`id_user`
                WHERE `u`.`id` != $id
                ORDER BY `full_name`;
            ";
        $result = $conn->query($q);
        if($result->num_rows == 0)
        {
    ?>
        <div class="error">No other users in database :(</div>
    <?php
        }
        else 
        {
            echo "<table>";
            echo "<tr><th>Name</th><th>Action</th></tr>";
            while($row = $result->fetch_assoc())
            {
                echo "<tr><td>";
                if($row["full_name"] !== NULL)
                {
                    echo $row["full_name"];
                }
                else 
                {
                    echo $row["username"];
                }
                echo "</td><td>";
                // Ovde cemo linkove za pracenje korisnika
                $friendId = $row["id"];
                if(!in_array($friendId, $niz1)) {
                    if(!in_array($friendId, $niz2)) {
                        $text = "Follow";
                    } else {
                        $text = "Follow back";
                    }
                    echo "<a href='followers.php?friend_id=$friendId'>$text</a>";
                } else {
                    echo "<a href='followers.php?unfriend_id=$friendId'>Unfollow</a>";
                }
                echo "</td></tr>";
            }
            
        }
    ?>
    Return to <a href="index.php">home page</a>.
</body>
</html>