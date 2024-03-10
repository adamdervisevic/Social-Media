<?php 
    session_start();
    require_once "connection.php";
    require_once "validation.php";
    require_once "header.php";

    $poruka = "";
    if (isset($_GET["p"]) && $_GET["p"] == "ok")
    {
        $poruka = "You have successfully registered, please login to continue";
    } 

    $username = "anonymus";
    if (isset($_SESSION["username"])) // da li je logovan korisnik
    {
        $username = $_SESSION["username"];
        $id = $_SESSION["id"]; // id logovanog korisnika
        $row = profileExists($id, $conn);
        $m = "";
        if ($row === false)
        {
            // Logovani korisnik nema profil
            $m = "Create";
        }
        else
        {
            // Logovani korisnik ima profil
            $m = "Edit";
            $username = $row["first_name"] . " " . $row["last_name"];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<body>
    <div class="container my-4">
        <div class="success">
            <?php echo $poruka; ?>
        </div>
        <h1>Welcome, <?php echo $username ?>, to our Social Network!</h1>
        <?php if (!isset($_SESSION["username"])) { ?>
            <p>New to our site? <a href="register.php">Register here</a> to access our site!</p>
            <p>Already have an account? <a href="login.php">Login here</a> to continue to our site!</p>
        <?php } else { ?>
            <p><?php echo $m ?> a <a href="profile.php">profile</a>.</p>
            <p>See other members <a href="followers.php">here</a>.</p>
            <p><a href="logout.php">Logout</a> from our site...</p>
        <?php } ?>
    <div class="container my-4">
</body> 
</body>
</html>