<?php

session_start();
include "../db_conn.php";
include "sendMail.php";

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

$domain = explode("@", $email);
if(count($domain) != 2){
    header("Location: index.php?error=Invalid email!");
    exit();
}

$domain = $domain[1];
$records = dns_get_record($domain, DNS_MX);
if (empty($records)) {
    header("Location: index.php?error=Invalid email!");
    exit();
}

if(strlen($password) < 8){
    header("Location: index.php?error=Password needs to have at least 8 characters!");
    exit();
}

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
    $currentDate = date('Y-m-d H:i:s');

    $token = hash('sha512', $email . $password . $fname . $lname);

    $sql = "SELECT email FROM accountConfirmation WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) != 0){
        header("Location: index.php?error=A link of confirmation was already sent to this email!");
        exit(); 
    }

    $insertSQL = "INSERT INTO accountConfirmation (token, email, fname, lname, password, date) VALUES ('$token', '$email', '$fname', '$lname', '$password', '$currentDate')";
    $wasInserted = mysqli_query($conn, $insertSQL);

    sendMailForAccountConfirmation($email, $token);

    header("Location: ../login/index.php?error=A link for account confirmation was sent on email!");
    exit();
} else {
    header("Location: index.php?error=An account with this email already exists!");
    exit();
}

?>