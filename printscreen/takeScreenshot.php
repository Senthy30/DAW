<?php

if(!(isset($_POST['urlToCapture']) && isset($_POST['nameToSave']) && isset($_POST['widthVal']) && isset($_POST['heightVal']))){
    exit();
}

$urlToCapture = $_POST['urlToCapture'];
$nameToSave = $_POST['nameToSave'];
$widthVal = $_POST['widthVal'];
$heightVal = $_POST['heightVal'];

$result = shell_exec('node screenshotWithPuppeteer.js ' . $urlToCapture . ' ' . $nameToSave . ' ' . $widthVal . ' ' . $heightVal);
 
echo "Succes";

?>