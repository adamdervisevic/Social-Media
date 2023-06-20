<?php
    require_once "header.php";
    $poruka = "";
    
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['m']))
    {
        $poruka = $_GET['m'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ooops! An error occured!</h1>
    <div class="error">
        <?php echo $poruka; ?>
    </div>
    Return to <a href="index.php">home page</a>.
</body>
</html>