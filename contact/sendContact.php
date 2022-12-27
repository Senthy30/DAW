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

    $email = "denisflorin69@yahoo.com";
    $title = $_POST['title'];
    $description = sanitize_input($_POST['description']);

    sendMail($email, $title, $description);
?>