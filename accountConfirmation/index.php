<?php
    include "../db_conn.php";

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    if(!isset($_GET['token'])){
        header("Location: ../index.php");
        exit();
    }

    $hashVal = validate($_GET['token']);

    $selectSQL = "SELECT * FROM accountConfirmation WHERE token = '$hashVal'";
    $result = mysqli_query($conn, $selectSQL);

    if(mysqli_num_rows($result) == 1){
        $result = $result->fetch_object();
        $email = $result->email;
        $fname = $result->fname;
        $lname = $result->lname;
        $password = $result->password;

        $date = strtotime($result->date);
        $currentDateTime = time();

        $deleteSQL = "DELETE FROM accountConfirmation WHERE token = '$hashVal';";
        $result = mysqli_query($conn, $deleteSQL);

        if(($currentDateTime - $date) / 60 <= 15){

            $insertSQL = "INSERT INTO users (email, fname, lname, password) VALUES ('$email', '$fname', '$lname', '$password')";
            $wasInserted = mysqli_query($conn, $insertSQL);

            if($wasInserted){
                $getNewIDSQL = "SELECT id FROM users WHERE email = '$email' AND password = '$password';";
                $newID = mysqli_query($conn, $getNewIDSQL);
                $newID = $newID->fetch_object();

                $insertSQL = "INSERT INTO permission (id_user, isAdmin) VALUES ('$newID->id', '0')";
                $wasInserted = mysqli_query($conn, $insertSQL);

                if($wasInserted){
                    header("Location: ../login/index.php?error=Registration successful!");
                    exit();
                }
            } 

        } else {
            header("Location: ../register/index.php?error=Link expired");
            exit();
        }
    } else {
        header("Location: ../index.php");
        exit();
    }
?>
