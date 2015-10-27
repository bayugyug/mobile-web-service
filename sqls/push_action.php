<?php

$sender    = $_POST['sender'];
$recipient = $_POST['recipient']; 
$message   = $_POST['message'];
$fullmessage = $_POST['fullmessage'];

try {
    $host   = "10.8.0.23";
    $dbname = "gses";
    $username = "gses";
    $password = "ph03n1x1";

    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('ERROR: ' . $e->getMessage());
}

$query = "select deviceToken,androidToken from jos_users where username = :user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$recipient);
$stmt->execute();

$userRec = $stmt->fetch();

// Put your device token here (without spaces):
// $deviceToken = '0f744707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bbad78';
// $deviceToken = '23f42eeab13a37382e6e35465c6960643d917937329ccc268b9bf340ea52d0d9';
$deviceToken = $userRec['deviceToken'];
$androidToken = $userRec['androidToken'];
echo $androidToken;
echo "Sending notification to user " . $recipient . " using device token " . $deviceToken . "\n";

// Private key passphrase
$passphrase = 'gt2p0rsche';

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'RCT.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
	'ssl://gateway.sandbox.push.apple.com:2195', $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp) {
	// exit("Failed to connect: $err $errstr" . PHP_EOL);
} else {
echo "Connected to Apple's Push Notification Server";

// Create the payload body
$body['aps'] = array(
	'alert' => $message,
	'sound' => 'default'
	);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
	echo 'Notification message not delivered\n' . PHP_EOL;
else
	echo 'Notification message successfully delivered\n' . PHP_EOL;

// Close the connection to the server
fclose($fp);
}

//Android Push Notifs

 $apiKey = "AIzaSyBnEuYHU2Bz6Cu2oB-Lb90whppSLnLA9eg";

    // Replace with real client registration IDs
    $registrationIDs = array($androidToken);
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


// Save this to the messages database
$host   = "10.8.0.23";
$dbname = "gses";
$username = "gses";
$password = "ph03n1x1";

try {
    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('ERROR: ' . $e->getMessage());
}

$query = "insert into messages(sender,recipient,message,status) values(:sender, :recipient, :message, :status)";
$stmt = $conn->prepare($query);
$stmt->bindParam(':sender',$sender);
$stmt->bindParam(':recipient',$recipient);
$stmt->bindParam(':message',$fullmessage);
$status = 0;
$stmt->bindParam(':status',$status);
$stmt->execute();


?>

