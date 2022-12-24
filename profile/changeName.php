<?php
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    session_start(); 
	include "../db_conn.php";

    if(!isset($_SESSION['id']) || !isset($_POST['lname']) || !isset($_POST['fname'])){
        exit();
    }

    $id = $_SESSION['id'];
    $fname = validate($_POST['fname']);
    $lname = validate($_POST['lname']);

    $sqlUpdate = "UPDATE users SET fname = '$fname', lname = '$lname' WHERE id = $id;";
    $result = mysqli_query($conn, $sqlUpdate);

    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;

    $_SESSION['message'] = "Name was changed!";

    exit();
?>