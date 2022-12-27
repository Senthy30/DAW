<?php
    session_start(); 
    include "../../db_conn.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    if(!isset($_POST['pass']) || !isset($_POST['confPass'])){
        header("Location: index.php?error=Error");
        exit();
    }

    $id = $_SESSION['id'];
    $pass = sanitize_input($_POST['pass']);
    if($pass != sanitize_input($_POST['confPass'])){
        header("Location: index.php?error=Passwords need to be the same");
        exit();
    }
    $email = $_SESSION['email'];
    $pass = hash('sha512', $pass);

    $sql = "SELECT id FROM users WHERE email = '$email' AND password = '$pass';";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        header("Location: index.php?error=Incorrect password");
        exit();
    } else {
        $result = $result->fetch_object();
        if($result->id != $id){
            header("Location: index.php?error=Error");
            exit();
        }
    }

    $sql = "DELETE FROM permission WHERE id_user = $id;";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM feedbacks WHERE id_user = $id;";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM rents WHERE id_user = $id;";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM visitors WHERE id_user = $id;";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM users WHERE id = $id;";
    $result = mysqli_query($conn, $sql);

    session_unset();
    session_destroy();

    header("Location: ../../index.php");
    exit();
?>