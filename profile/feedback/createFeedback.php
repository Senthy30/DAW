<?php
    session_start(); 
    include "../../db_conn.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    if(!isset($_POST['note']) || !is_numeric($_POST['note']) || !isset($_POST['idRoom']) || !is_numeric($_POST['idRoom']) || !isset($_POST['description'])){
        header("Location: index.php");
        exit();
    }

    $idSession = $_SESSION['id'];
    $idRoom = $_POST['idRoom'];
    $note = $_POST['note'];
    $description = sanitize_input($_POST['description']);

    $sql = "SELECT * FROM rents WHERE id_user = $idSession AND id_room = $idRoom;";
    $resultQuery = mysqli_query($conn, $sql);
    if(mysqli_num_rows($resultQuery) == 0){
        header("Location: ../index.php");
        exit();
    }

    $sqlInsert = "INSERT INTO feedbacks (id_user, id_room, note, description) VALUES ($idSession, $idRoom, $note, '$description')";
    $result = mysqli_query($conn, $sqlInsert);

    $_SESSION['message'] = "Feedback was submitted successfully!";

    header("Location: ../index.php");
    exit();
?>