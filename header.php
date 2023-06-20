<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <ul class="menu <?php echo isset($_SESSION['user_id']) ? 'logovani' : 'nelogovani'; ?>">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="followers.php">Followers</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php } else { ?>
            <li><a href="register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
        <?php } ?>
    </ul>
</body>
</html>




