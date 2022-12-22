<?php
    function sendMailForAccountConfirmation($email){
        $bodyEmail = "Hello, <br>
                    To activate your account, you must confirm your email address. <br> <br>
        
                    Please click the following link: <br>
                    .... <br><br>
                    
                    If the above URL does not work, please try to copy-paste the entire link in your browser's address bar manually. <br> <br>

                    If you are still having problems with your account please email.";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $_ENV['TRUSTIFI_URL'] . "/api/i/v1/email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\"recipients\":[{\"email\":\"" . $email . "\"}],\"title\":\"Account confirmation\",\"html\":\"body\"}",
            CURLOPT_HTTPHEADER => array(
                "x-trustifi-key: " . $_ENV['TRUSTIFI_KEY'],
                "x-trustifi-secret: " . $_ENV['TRUSTIFI_SECRET'],
                "content-type: application/json"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    sendMailForAccountConfirmation("denisflorin69@yahoo.com");
?>