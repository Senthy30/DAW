<?php
    session_start(); 
	include "../db_conn.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    $isOk = (!isset($_POST['idRoom']) || !is_numeric($_POST['idRoom']));
    $isOk |= (!isset($_POST['startDate']) || empty(validateDate($_POST['startDate'])));
    $isOk |= (!isset($_POST['endDate']) || empty(validateDate($_POST['endDate'])));

    echo $_POST['idRoom'] . " - " . $_POST['startDate'] . " - " . $_POST['endDate'];

    if($isOk){
        //header("Location: index.php");
        //exit();
    }

    $idRoom = $_POST['idRoom'];
    $idSession = $_SESSION['id'];
    $startDate = new DateTime($_POST['startDate']);
    $startDate = $startDate->format('Y-m-d');
    $endDate = new DateTime($_POST['endDate']);
    $endDate = $endDate->format('Y-m-d');

    $sqlInsert = "INSERT INTO rents (id_user, id_room, startDate, endDate) VALUES ($idSession, $idRoom, $startDate, $endDate)";
    $resultInsert = mysqli_query($conn, $sqlInsert);

    print_r($resultInsert);
?>