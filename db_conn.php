<?php

$servername = "eu-cdbr-west-03.cleardb.net";
$rootname = "b21e39a3536372";
$rootpassword = "21d0bb20";

$db_name = "heroku_6042fda7f3f745a";
$conn = mysqli_connect($servername, $rootname, $rootpassword, $db_name);

if(!$conn){
    echo "Connection Failed";
}

?>