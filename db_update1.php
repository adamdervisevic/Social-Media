    <?php

            require_once "connection.php";

            $sql = "ALTER TABLE `profiles`
            ADD COLUMN `bio` TEXT;";

    if($conn->multi_query($sql))
    {
    echo "<p>profiles updated with new column successfully</p>";
    }
    else
    {
    header("Location: error.php?m=" . $conn->error);
    }
    ?>