<?php
    session_start(); 
    include "../../db_conn.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    if(!isset($_SESSION['id']) || !isset($_POST['pass']) || !isset($_POST['confPass']) || !isset($_POST['idRoom']) || !is_numeric($_POST['idRoom'])){
        header("Location: index.php?error=Error");
        exit();
    }

    $id = $_SESSION['id'];
    $sql = "SELECT isAdmin FROM permission WHERE id_user = $id;";
    $result = mysqli_query($conn, $sql);
    $result = $result->fetch_object();
    if($result->isAdmin == 0){
        header("Location: ../../index.php");
        exit();
    }

    $idRoom = $_POST['idRoom'];
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

    $sql = "DELETE FROM rents WHERE id_room = $idRoom;";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM visitors WHERE id_room = $idRoom;";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM feedbacks WHERE id_room = $idRoom;";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM rooms WHERE id = $idRoom;";
    $result = mysqli_query($conn, $sql);

    $_SESSION['message'] = "The room was deleted successfully!";
    header("Location: ../../profile/index.php");
    exit();
?>