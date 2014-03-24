<?php
require_once(__DIR__.'/../config/required.php');
require_once(__DIR__."/../Manager/MeetingManager.class.php"); 
require_once(__DIR__."/../Manager/AttendeesMeetingManager.class.php");   
echo "bavaadad";
echo $_POST['title'];
if(isset($_POST["MeetingEditing"])){  
	echo "ffffffffff";
	$options = array(
		'title' => FILTER_SANITIZE_STRING,
		'startDate' => FILTER_SANITIZE_STRING,
		'finishDate' => FILTER_SANITIZE_STRING,
		'startTime' => FILTER_SANITIZE_STRING,
		'finishTime' => FILTER_SANITIZE_STRING,
		'allDay' => FILTER_SANITIZE_STRING,
		'repeatM' => FILTER_SANITIZE_STRING,
		'colorM' => FILTER_SANITIZE_STRING,
		'place' => FILTER_SANITIZE_STRING,
		'description' => FILTER_SANITIZE_STRING,
	);

	$result = filter_input_array(INPUT_POST, $options);

	if($result != null) { //check if the form is sent
	   
	    $nbrErreurs = 0;	

	    if (empty($_POST["title"])) {
	    	echo" Missing title";$nbrErreurs++;
	    }elseif( (!empty($_POST["startDate"]) || !empty($_POST["finishDate"]) ) && !empty($_POST["allDay"])){
	   		echo "Conflicting date : all day option and date can't be chosen together."; 
	   		$nbrErreurs++;
	    }

	    foreach($options as $cle => $valeur) {
	        if($result[$cle] === false) { // not valid input
	        echo "The ".$cle . "format is not valid";
	        $nbrErreurs++;
	        }
	    }

	   	$userId = $_SESSION['user_id'];
	    if($nbrErreurs == 0 && $_SESSION['user_credential']==1) {
		     $dataMeeting = array(
			'title' => $result['title'],
			'startDate' => $result['startDate'],
			'finishDate' => $result['finishDate'],
			'startTime' => $result['startTime'],
			'finishTime' => $result['finishTime'],
			'allDay' => $result['allDay'],
			'repeatM' => $result['repeatM'],
			'colorM' => $result['colorM'],
			'place' => $result['place'],
			'description' => $result['description'],
			'organizerId' => $userId
			);
	    }
	}
	else {
	    echo "Empty form";
	}

	$meetingManager = new MeetingManager($db);
	$attMeetManager = new AttendeesMeetingManager($db);
	$meeting = new Meeting($dataMeeting);
	$meetingManager->edit($meeting);
	$lastInsertM = $db->lastInsertId();
	$_SESSION['lastInsertM'] = $lastInsertM;
	echo "string";
	$emailList = $attMeetManager->getEmailsByMeetingId($lastInsertM);

}

?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Invite attendees</title>
</head>
<form action="inviteProcess.php" method="POST" name="MeetingCreation">

<h2>Invite people: </h2>

<div>
List of attendees
<textarea name="attendees" cols="25" rows="5">
<?php
/*
echo $emailList['0'] ;*/
/*foreach ($emailList as $value) {
	echo $value.", ";
}
*/
echo "adadd";
?>
</textarea>
<br/>
Email have to be written followed by semicolon.
</div>
<input type="submit" value = "Finish">
</form>





