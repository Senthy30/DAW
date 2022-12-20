<?php

session_start();
include "../db_conn.php";

if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmpassword']) && isset($_POST['fname']) && isset($_POST['lname'])) {

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
}

$email = validate($_POST['email']);
$fname = validate($_POST['fname']);
$lname = validate($_POST['lname']);
$password = validate($_POST['password']);
$confirmpassword = validate($_POST['confirmpassword']);

if($password != $confirmpassword){
    header("Location: index.php?error=Passwords need to be the same!");
    exit();
}

if(empty($email)){
    header("Location: index.php?error=Email is required!");
    exit();
} else if(empty($fname)){
    header("Location: index.php?error=Password is required!");
    exit();
} else if(empty($lname)){
    header("Location: index.php?error=Last name is required!");
    exit();
} else if(empty($password)){
    header("Location: index.php?error=First name is required!");
    exit();
} else if(empty($confirmpassword)){
    header("Location: index.php?error=Confirm password is required!");
    exit();
}

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) === 0){
    $password = hash('sha512', $password);
    $insertSQL = "INSERT INTO users (email, fname, lname, password) VALUES ('$email', '$fname', '$lname', '$password')";
    $wasInserted = mysqli_query($conn, $insertSQL);

    $getNewIDSQL = "SELECT ID FROM users WHERE email = '$email' and password = '$password';";
    $newID = mysqli_query($conn, $getNewIDSQL);

    echo $newID;

    if($wasInserted && false){
        header("Location: ../login/index.php?error=Registration successful!");
        exit();
    } else if(false) {
        header("Location: index.php?error=Something goes bad!");
        exit();
    }
} else {
    header("Location: index.php?error=An account with this email already exists!");
    exit();
}

?>