<?php
    session_start(); 
    include "../../db_conn.php";
    include "../register/sendMail.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    if(!isset($_POST['title']) || !isset($_POST['description'])){
        header("Location: index.php");
        exit();
    }

    $emailSender = $_SESSION['email'];
    $email = "denisflorin69@yahoo.com";
    $title = $_POST['title'];
    $description = "Title: $title <br><br> From: $emailSender <br><br> Message: ";
    $description = $description . sanitize_input($_POST['description']);

    sendMail($email, "Contact us", $description);

    $_SESSION['message'] = "The message was sent successfully!";

    header("Location: ../profile/index.php");
    exit();
?>