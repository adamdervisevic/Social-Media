<?php
require_once "connection.php";
require_once "validation.php";
session_start();

if (empty($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$id = null;

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $p = "SELECT * FROM `users` WHERE `id` = $id";
    $result1 = $conn->query($p);

    if ($result1->num_rows == 0) {
        echo "<p class='error'>User doesn't exist</p>";
        exit;
    } else {
        $row1 = $result1->fetch_assoc();
        $username = $row1['username'];
    }
}

if (isset($_SESSION["username"])) {
    $userName = $_SESSION["username"];
    $id2 = $_SESSION["id"];
    $row2 = profileExists($id2, $conn);

    if ($row2 === false) {
        $userName = "Profile not found";
    } else {
        $userName = $row2["first_name"] . " " . $row2["last_name"];
    }
}

$q = "SELECT * FROM `profiles` WHERE `id_user` = $id";
$result = $conn->query($q);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Show Profile</title>
</head>
<body>
    <header>
        <?php require_once "header.php"; ?>
    </header>
    
    <p class="greeting">Hello, <?php echo $userName; ?></p>

    <?php
    if ($result === false) {
        echo "<p class='error'>An error occurred while retrieving the profile</p>";
    } elseif ($result->num_rows == 0) {
        echo "<p class='error'>User doesn't have a profile</p>";
    } else {
        $row = $result->fetch_assoc();
        $name = $row['first_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];
        $about = $row['bio'];
        $dob = $row['dob'];

        $color = '';
        if ($gender == 'm') {
            $color = 'blue';
        } elseif ($gender == 'f') {
            $color = 'pink';
        } else {
            $color = 'gray';
        }
        ?>

        <table class="profile-table" style="color: <?php echo $color; ?>">
            <tr>
                <td>First Name</td>
                <td><?php echo $name; ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo $last_name; ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo $username; ?></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><?php echo $dob; ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo $gender; ?></td>
            </tr>
            <tr>
                <td>About Me</td>
                <td><?php echo $about; ?></td>
            </tr>
        </table>
    <?php } ?>

    <p>Go to <a href="followers.php">Followers</a></p>
</body>
</html>