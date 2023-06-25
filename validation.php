<?php

    function usernameValidation($u, $c)
    {
        $query = "SELECT * FROM `users` WHERE `username` = '$u'";
        $result = $c->query($query);

        if (empty($u))
        {
            return "Username cannot be blank";
        }
        elseif (preg_match('/\s/', $u))
        {
            return "Username cannot contain spaces";
        }
        elseif (strlen($u) < 4 || strlen($u) > 25)
        {
            return "Username must be between 4 and 25 characters";
        }
        elseif ($result->num_rows > 0)
        {
            return "Username is reserved, please choose another one";
        }
        else
        {
            return "";
        }
    }

    function passwordValidation($u)
    {
        if (empty($u))
        {
            return "Password cannot be blank";
        }
        elseif (preg_match('/\s/', $u))
        {
            return "Password cannot contain spaces";
        }
        elseif (strlen($u) < 5 || strlen($u) > 50)
        {
            return "Password must be between 5 and 50 characters";
        }
        else
        {
            return "";
        }
    }

    function nameValidation($n)
    {
        $n = str_replace(' ', '', $n);
        if (empty($n))
        {
            return "Name cannot be empty";
        }
        elseif (strlen($n) > 50)
        {
            return "Name cannot contain more than 50 characters";
        }
        elseif (preg_match("/^[a-zA-ZŠšĐđŽžČčĆć]+$/", $n) == false)
        {
            return "Name must contain only letters";
        }
        else
        {
            return "";
        }
    }

    function genderValidation($g)
    {
        if($g != "m" && $g != "f" && $g != "o")
        {
            return "Unknown gender";
        }
        else
        {
            return "";
        }
    }

    function dobValidation($d)
    {
        if (empty($d))
        {
            return ""; // ok je da dob bude prazno
        }
        elseif ($d < "1900-01-01")
        {
            return "Date of birth not valid";
        }
        else
        {
            return "";
        }
    }

    function profileExists($id, $conn)
    {
        $q = "SELECT * FROM `profiles` WHERE `id_user` = $id";
        $result = $conn->query($q);
        if ($result->num_rows == 0)
        {
            return false;
        }
        else
        {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    function imageValidation($image) {
        $allowed = ['gif', 'png', 'jpg', 'jpeg'];
        $file = pathinfo($image, PATHINFO_EXTENSION);
        if(!in_array($file, $allowed)) {
            return 'Invalid file';
        } elseif(empty($image)) {
            return "Please select an image.";
        } else {
            return "";
        }
    }

    function getGender($receiverId, $conn){
            $sql = "SELECT `gender` FROM `profiles` WHERE `id_user` = $receiverId";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row["gender"];
            } else {
                return "other";
            }
        }
?>