<?php

    function updateExchangeValue($prefixFile) {
        $endpoint = "https://api.exchangerate-api.com/v4/latest/RON";
        $api_key = "7a57f1f089d003a79d0fd97d";

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $api_key
        );

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($response_code == 200) {
            $response_obj = json_decode($response);

            $filename = $prefixFile . "exchangeValues.txt";
            $file = fopen($filename, "w");

            $json = json_encode($response_obj->rates);

            file_put_contents($filename, $json);
            fclose($file);

        } else {
            echo "Error making request. Response code: $response_code";
        }
    }

    $typeCurrency = "RON";
    $exchangeRate = 1;

    function getCurrency($prefixFile){
        global $typeCurrency, $exchangeRate;

        $filename = $prefixFile . "lastUpdateExchangeValues.txt";

        $lastDate = new DateTime(file_get_contents($filename));
        $currentDate = new DateTime();
        
        $diff = $currentDate->diff($lastDate)->format("%a");
        if($diff > 0){
            updateExchangeValue($prefixFile);
            file_put_contents($filename, date("Y-m-d"));
        }

        $filename = $prefixFile . "exchangeValues.txt";
        $json = file_get_contents($filename);
        $obj = json_decode($json);

        $typeCurrency = $_SESSION['currency'];
        $exchangeRate = $obj->$typeCurrency;
    }

?>