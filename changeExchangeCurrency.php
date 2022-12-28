<?php

    session_start();

    $listOfCurrency = ["RON", "EUR", "GBP", "USD"];
    if(!isset($_POST['valCurrency']) || !in_array($_POST['valCurrency'], $listOfCurrency)){
        header("Location: index.php");
        exit();
    }

    $_SESSION['currency'] = $_POST['valCurrency'];
    
    header("Location: index.php");
    exit();
?>