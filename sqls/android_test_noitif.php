
<?php

// Replace with real server API key from Google APIs        
    $apiKey = "AIzaSyBnEuYHU2Bz6Cu2oB-Lb90whppSLnLA9eg";

    // Replace with real client registration IDs
    $registrationIDs = array("APA91bECZooLY54AQ2pY8EhGDDB3ovHfAmRCdLcAabcY3eHYNu5XktaS0_m_TxGAIj-d5U8uq95Ci3aXDbkC3t7s0yiuyyKCGODG-KrWj2HAw_Ij-_ivciDHYHY4U45x4CgF4JbqHAJEuj3TorJApdAddtsy3s4IPg");
    // Message to be sent
    $message = "Test Notificación PHP";

    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';

    $fields = array(
        'registration_ids' => $registrationIDs,
        'data' => array( "message" => $message ),
    );

    $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    );

    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_POST, true);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ));

    // Execute post
    $result = curl_exec($ch);

    // Close connection
    curl_close($ch);
    echo $result;
    //print_r($result);
    //var_dump($result);

?>
