<?php
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    session_start(); 
	include "../db_conn.php";

    if(!isset($_SESSION['id']) || !isset($_POST['currPass']) || !isset($_POST['newPass']) || !isset($_POST['confNewPass'])){
        $_SESSION['message'] = "Error!";
        header("Location: index.php");
        exit(); 
    }

    $id = $_SESSION['id'];
    $email = $_SESSION['email'];
    $currPass = validate($_POST['currPass']);
    $newPass = validate($_POST['newPass']);
    $confNewPass = validate($_POST['confNewPass']);

    if($newPass != $confNewPass){
        $_SESSION['message'] = "Passwords need to be the same!";
        header("Location: index.php");
        exit(); 
    }

    $hashPassword = hash('sha512', $currPass);
    $sqlQuery = "SELECT id FROM users WHERE email = '$email' AND password = '$hashPassword'";
    $result = mysqli_query($conn, $sqlQuery);

    if(mysqli_num_rows($result) === 1){
        $hashPassword = hash('sha512', $newPass);
        $sqlUpdate = "UPDATE users SET password = '$hashPassword' WHERE id = $id;";
        $result = mysqli_query($conn, $sqlUpdate);

        $_SESSION['message'] = "Password was changed!";
        exit(); 
    } else {
        $_SESSION['message'] = "Incorrect current password!";
        exit(); 
    }

    exit(); 
?>