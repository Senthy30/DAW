<?php

    session_start();
    include "../../db_conn.php";

    if(!isset($_SESSION['id'])) {
        header("Location: ../../index.php");
        exit();
    }

    $idSession = $_SESSION['id'];
    $sql = "SELECT isAdmin FROM permission WHERE id_user = $idSession;";
    $result = mysqli_query($conn, $sql);
    $result = $result->fetch_object();
    if($result->isAdmin == 0){
        header("Location: ../index.php");
        exit();
    }

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $information = ["valStars", "valFloor", "valNumber", "valType", "valSurface", "valAdvance", "valCancellation", "valFood", "valPrice"];

    $validPost = isset($_FILES['image']) && isset($_POST['roomName']);
    $fileTmpName = 0;
    if(isset($_FILES['image'])){
        $file = $_FILES['image'];

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileType = exif_imagetype($fileTmpName);
        $validPost &= ($fileType === IMAGETYPE_JPEG || $fileType === IMAGETYPE_PNG);

        $fileExt = explode('.', $fileName);
        $fileExt = strtolower(end($fileExt));
        $allowedExtensions = array('jpg', 'jpeg', 'png');
        $validPost &= in_array($fileExt, $allowedExtensions);
    }

    for($i = 0; $i < count($information); $i++)
        $validPost &= isset($_POST[$information[$i]]) && is_numeric($_POST[$information[$i]]);
    
    if(!$validPost){
        header("Location: ../index.php");
        exit();
    }
    
    $sql = "SELECT MAX(id) as AI FROM rooms;";
    $result = mysqli_query($conn, $sql);
    $result = $result->fetch_object();
    $idRoom = ($result->AI) + 10;

    $dirName = "id_" . $idRoom . "/";
    $dirPath = "../../img/rooms/" . $dirName;

    if(!is_dir($dirPath))
        mkdir($dirPath, true);

    $fileDestination = $dirPath . '1.jpg';
    move_uploaded_file($fileTmpName, $fileDestination);

    $roomName = validate($_POST['roomName']);
    $values = array();
    for($i = 0; $i < count($information); $i++)
        $values[] = $_POST[$information[$i]];
    
    $sql = "INSERT INTO rooms (id, name, stars, floor, number, type, surface, advance, cancellation, food, price, accesses) VALUES ($idRoom, '$roomName', $values[0], $values[1], $values[2], $values[3], $values[4], $values[5], $values[6], $values[7], $values[8], 0);";
    $result = mysqli_query($conn, $sql);
    
    $_SESSION['message'] = "The room was added successfully!";
    header("Location: ../../profile/index.php");
    exit();
?>