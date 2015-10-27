<?php
require_once('db.inc');
require_once('settings.inc');
require_once('nusoap/nusoap.php');

$user  = $_GET['username'];

// Check required parameters
if ( !$user ) {
   die('ERROR: No parameters passed');
}

// Check if a connection was established
if (!$conn) {
   die("No connection");
}

//CHECK
$query = "select username,id from tbl_users where username = :user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$user);
$stmt->execute();
$userRec = $stmt->fetch();

//Check if user exists
if($userRec != null)
{
    
    // $resetToken = mt_rand_str(32);
       $resetToken = mt_rand_str(4);
    
       $query2 = "update tbl_users_mobile set resetToken = :token where user_id = :user";
       $stmt3 = $conn->prepare($query2);
       $stmt3->bindParam(':token',$resetToken);
       $stmt3->bindParam(':user',$userRec['id']);
       $stmt3->execute();
	
		//Get Questions
		$querySelectQuestions  = "select profile_value from tbl_user_profiles where user_id = '".$userRec['id']."' and profile_key like 'profile.question%' order by profile_key asc";	
		$stmt2 = $conn->prepare($querySelectQuestions);
		$stmt2->execute();
		$questions = $stmt2->fetchAll();
    
        $querySelectAnswers  = "select profile_value from tbl_user_profiles where user_id = '".$userRec['id']."' and profile_key like 'profile.answer%' order by profile_key asc";	
		$stmt4 = $conn->prepare($querySelectAnswers);
		$stmt4->execute();
		$answers = $stmt4->fetchAll();
		
	
        $ret['q1']['qs_text'] = $questions[0]['profile_value'];
        $ret['q2']['qs_text'] = $questions[1]['profile_value'];
        $ret['q1']['answer'] = $answers[0]['profile_value'];
        $ret['q2']['answer'] = $answers[1]['profile_value'];
        $ret['token'] = $resetToken;
    
	echo json_encode($ret);
}
else
{
	 echo json_encode(array("Error"=>"Username does not exist."));
}


function mt_rand_str ($l, $c = 'abcdef1234567890') {
    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}


?>