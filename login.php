<?php
    session_start(); // Ova f-ja treba na pocetku (kao prva) da se pozove
    if(isset($_SESSION["id"]))
    {
        header("Location: index.php");
    }
    // Cim treba nekako da koristimo sesiju, mora ova f-ja da se pozove
    require_once "connection.php";
    require_once "header.php";

    $usernameError = "*";
    $passwordError = "*";
    $username = "";    
    

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Korisnik je poslao username i password i pokusava logovanje
        $username = $conn->real_escape_string($_POST["username"]);
        $password = $conn->real_escape_string($_POST["password"]);

        // Vrsimo razlicite validacije
        if(empty($username))
        {
            $usernameError = "Username cannot be blank!";
        }
        if(empty($password))
        {
            $passwordError = "Password cannot be blank!";
        }
        if ($usernameError == "*" && $passwordError == "*")
        {
            // Ovde mozemo da pokusamo da logujemo korisnika
            // (ako svi kredencijali za logovanje se podudaraju)
            $q = "SELECT * FROM `users` WHERE `username` = '$username'";
            $result = $conn->query($q);
            if ($result->num_rows == 0)
            {
                $usernameError = "This username doesn't exist!";
            }
            else 
            {
                // Postoji takav korisnik, proveriti lozinke
                $row = $result->fetch_assoc();
                $dbPassword = $row["password"];  // heshirana vrednost iz baze
                if (!password_verify($password, $dbPassword))
                {
                    // Poklopili su se username, ali lozinka nije dobra
                    $passwordError = "Wrong password, try again!";
                }
                else
                {
                    // Dobri su i username i password, izvrsi logovanje
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["username"] = $row["username"];
                    header("Location: index.php");
                }
            }
        }

        // Ako je sve u redu, loguj korisnika
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Please login</h1>
    <form action="#" method="post">
        <p>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo $username;?>">
            <span class="error"><?php echo $usernameError; ?></span>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <span class="error"><?php echo $passwordError; ?></span>
        </p>
        <p>
            <input type="submit" value="Login">
        </p>
    </form>
</body>
</html>