<?php
    session_start(); 
	include "../db_conn.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
        exit();
    }

    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    $isOk = (!isset($_POST['idRoom']) || !is_numeric($_POST['idRoom']));
    $isOk |= (!isset($_POST['startDate']) || empty(validateDate($_POST['startDate'])));
    $isOk |= (!isset($_POST['endDate']) || empty(validateDate($_POST['endDate'])));

    if($isOk){
        header("Location: index.php");
        exit();
    }

    $idRoom = $_POST['idRoom'];
    $idSession = $_SESSION['id'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $isAvailable = true;
    $sqlDisp = "SELECT * FROM rents WHERE id_room = $idRoom AND startDate <= '$startDate' AND endDate > '$startDate';";
    $resultDisp = mysqli_query($conn, $sqlDisp);
    $isAvailable &= (mysqli_num_rows($resultDisp) == 0);

    $sqlDisp = "SELECT * FROM rents WHERE id_room = $idRoom AND startDate < '$endDate' AND endDate > '$endDate';";
    $resultDisp = mysqli_query($conn, $sqlDisp);
    $isAvailable &= (mysqli_num_rows($resultDisp) == 0);

    $sqlDisp = "SELECT * FROM rents WHERE id_room = $idRoom AND startDate >= '$startDate' AND endDate < '$endDate';";
    $resultDisp = mysqli_query($conn, $sqlDisp);
    $isAvailable &= (mysqli_num_rows($resultDisp) == 0);

    $startDate = new DateTime($_POST['startDate']);
    $startDate = $startDate->format('Y-m-d');
    $endDate = new DateTime($_POST['endDate']);
    $endDate = $endDate->format('Y-m-d');

    if(!$isAvailable){
        header("Location: ../index.php");
        exit();
    }

    $sqlInsert = "INSERT INTO rents (id_user, id_room, startDate, endDate) VALUES ($idSession, $idRoom, '$startDate', '$endDate')";
    $resultInsert = mysqli_query($conn, $sqlInsert);

    $_SESSION['message'] = "You rent successfully a room!";

    header("Location: ../profile/index.php");
    exit();
?>