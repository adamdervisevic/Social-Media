<?php
    session_start();
    if(!isset($_SESSION["id"]))
    {
        header("Location: index.php");
    }

    $id = $_SESSION["id"];
    $firstName = $lastName = $dob = $gender = $image = "";
    $firstNameError = $lastNameError = $dobError = $genderError = $imageError = "";

    $uploadDirectory = 'uploads/';

    

    $sucMessage = "";
    $errMessage = "";

    require_once "connection.php";
    require_once "validation.php";
    require_once "header.php";

    $profileRow = profileExists($id, $conn);
    // $profileRow = false, ako profil ne postoji
    // $profileRow = asocijativni niz, ako profil postoji
    if ($profileRow !== false)
    {
        $firstName = $profileRow["first_name"];
        $lastName = $profileRow["last_name"];
        $gender = $profileRow["gender"];
        $dob = $profileRow["dob"];
        $image = $profileRow["image"];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $firstName = $conn->real_escape_string($_POST["first_name"]);
        $lastName = $conn->real_escape_string($_POST["last_name"]);
        $gender = $conn->real_escape_string($_POST["gender"]);
        $dob = $conn->real_escape_string($_POST["dob"]);
        $image = $conn->real_escape_string($_FILES["profile-image"]["name"]);

        // Vrsimo validaciju polja
        $firstNameError = nameValidation($firstName);
        $lastNameError = nameValidation($lastName);
        $genderError = genderValidation($gender);
        $dobError = dobValidation($dob);
        $imageError = imageValidation($image);

        // Ako je sve u redu, ubacujemo novi red u tabelu `profiles`
        if ($firstNameError == "" && $lastNameError == "" && $genderError == ""
            && $dobError == "")
        {
            $q = "";
            if ($profileRow === false)
            {
                $q = "INSERT INTO `profiles`(`first_name`, `last_name`, `gender`, `dob`, `id_user`)
                    VALUE
                    ('$firstName', '$lastName', '$gender', '$dob', $id)";
            }
            else
            {
                $q = "UPDATE `profiles`
                    SET `first_name` = '$firstName',
                    `last_name` = '$lastName',
                    `gender` = '$gender',
                    `dob` = '$dob'
                    WHERE `id_user` = $id
                    ";
            }
            
            if ($conn->query($q))
            {
                // Uspesno kreiran ili editovan profil
                if($profileRow !== false)
                {
                    $sucMessage = "You have edited your profile";
                }
                else
                {
                    $sucMessage = "You have created your profile";
                }
                
            }
            else
            {
                // Desila se greska u upitu
                $errMessage = "Error creating profile: " . $conn->error;
            }

            if (isset($_FILES["profile-image"]) && $_FILES["profile-image"]["error"] == 0) {
                $imageName = $_FILES["profile-image"]["name"];
                $uploadPath = $uploadDirectory . basename($imageName); 
            
                if (!file_exists($uploadPath)) {
                    if (move_uploaded_file($_FILES["profile-image"]["tmp_name"], $uploadPath)) {
                        $updateQuery = "UPDATE profiles SET image = '$uploadPath' WHERE id_user = $id";
                        if ($conn->query($updateQuery)) {
                            $sucMessage = "Profile information updated successfully.";
                        } else {
                            $errMessage = "Error updating profile information: " . $conn->error;
                        }
                    } else {
                        $errMessage = "Error uploading the image: " . $_FILES["profile-image"]["error"];
                    }
                } else {
                    $errMessage = "File already exists.";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="success">
        <?php echo $sucMessage; ?>
    </div>
    <div class="error">
        <?php echo $errMessage; ?>
    </div>
    <h1>Please fill the profile details</h1>
    <form action="#" method="post" enctype="multipart/form-data">
        <p>
            <label for="first_name">First name:</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo $firstName; ?>">
            <span class="error">* <?php echo $firstNameError; ?></span>
        </p>
        <p>
            <label for="last_name">Last name:</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo $lastName; ?>">
            <span class="error">* <?php echo $lastNameError; ?></span>
        </p>
        <p>
            <label for="gender">Gender:</label>
            <input type="radio" name="gender" id="m" value="m" <?php if($gender == "m") { echo "checked"; } ?>> Male
            <input type="radio" name="gender" id="f" value="f" <?php if($gender == "f") { echo "checked"; } ?>> Female
            <input type="radio" name="gender" id="o" value="o" <?php if($gender == "o" || $gender == "") { echo "checked"; } ?>> Other
            <span class="error"><?php echo $genderError; ?></span>        
        </p>
        <p>
        <label for="profile-image">Profile Image:</label>
            <input type="file" name="profile-image" id="profile-image">
            <?php
                // Add the code below to display the user's profile image
                if($image) {
                    echo "<img src='".$image."' width='100' height='100'/>";
                }
            ?>
        </p>
        <div class="success">
            <?php echo $sucMessage; ?>
        </div>
        <div class="error">
            <?php echo $errMessage; ?>
        </div>
        <p>
            <label for="dob">Date of birth:</label>
            <input type="date" name="dob" id="dob" value="<?php echo $dob; ?>">
            <span class="error"><?php echo $dobError; ?></span>
        </p>
        <p>
            <?php
                $poruka;
                if($profileRow === false)
                {
                    $poruka = "Create profile";
                }
                else
                {
                    $poruka = "Edit profile";
                }
                //echo "<input type='submit' value='$poruka'>";
            ?>
            <!--input type="submit" value="<?php //echo $poruka ?>"-->
            <input type="submit" value="<?php echo ($profileRow === false) ? 'Create profile' : 'Edit profile' ?>">
        </p>
    </form>
    <p>
        Go back to <a href="index.php">home page</a>.
    </p>
</body>
</html>