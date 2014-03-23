<?php

//function loadClass($classe){ require '../Model/'.$classe . '.class.php'; }

require_once('../Model/Meeting.class.php');
//
class MeetingManager{

	private $_db;

	public function __construct($db){
		$this->_db=$db;
	}

	public function add(Meeting $m){
		try {
	    $q = $this->_db->prepare('INSERT INTO meetings SET title = :title, startDate = :startDate, finishDate = :finishDate, -startTime = :startTime, finishTime = :finishTime, place = :place, organizer_id= :organizerId, duration = :duration, description= :description, repeatM = :repeatM, colorM = :colorM');

	    $q->bindValue(':title', $rdv->title(), PDO::PARAM_STR);
	    $q->bindValue(':startDate', $rdv->getStartDate(), PDO::PARAM_STR);
	    $q->bindValue(':finishDate', $rdv->getFinishDate(), PDO::PARAM_STR);
	    $q->bindValue(':startTime', $rdv->getStartTime(), PDO::PARAM_STR);
	    $q->bindValue(':finishTime', $rdv->getFinishTime(), PDO::PARAM_STR);
	    $q->bindValue(':place', $rdv->getPlace(), PDO::PARAM_STR);
	    $q->bindValue(':organizerId', $rdv->getOrganizerId(), PDO::PARAM_INT);
	    $q->bindValue(':duration', $rdv->getDuration(), PDO::PARAM_STR);
	    $q->bindValue(':description', $rdv->getDescription(), PDO::PARAM_STR);
	    $q->bindValue(':repeatM', $rdv->getRepeatM(), PDO::PARAM_STR);
	    $q->bindValue(':colorM', $rdv->getColorM(), PDO::PARAM_STR);
	    
	    $q->execute();
		}catch (PDOException $e){
		echo 'Erreur : ',  $e->getMessage();
		}
	}

	

	public function delete($id){
		$this->_db->exec('DELETE FROM meetings WHERE id = '.$id);
	}

	public function edit(Meeting $m){
		$q = $this->_db->prepare('UPDATE meetings SET title = :title, startDate = :startDate, finishDate = :finishDate, startTime = :startTime, finishTime = :finishTime, place = :place, organizer_id= :organizerId, duration = :duration, description= :description, repeatM = :repeatM, colorM = :colorM WHERE id = :id');
  		$q->bindValue(':title', $rdv->title(), PDO::PARAM_STR);
	    $q->bindValue(':startDate', $rdv->getStartDate(), PDO::PARAM_STR);
	    $q->bindValue(':finishDate', $rdv->getFinishDate(), PDO::PARAM_STR);
	    $q->bindValue(':startTime', $rdv->getStartTime(), PDO::PARAM_STR);
	    $q->bindValue(':finishTime', $rdv->getFinishTime(), PDO::PARAM_STR);
	    $q->bindValue(':place', $rdv->getPlace(), PDO::PARAM_STR);
	    $q->bindValue(':organizerId', $rdv->getOrganizerId(), PDO::PARAM_INT);
	    $q->bindValue(':duration', $rdv->getDuration(), PDO::PARAM_STR);
	    $q->bindValue(':description', $rdv->getDescription(), PDO::PARAM_STR);
	    $q->bindValue(':repeat', $rdv->getRepeat(), PDO::PARAM_STR);
	    $q->bindValue(':colorM', $rdv->getColorM(), PDO::PARAM_STR);
    	$q->bindValue(':id', $rdv->getId(), PDO::PARAM_INT);
    	$q->execute();
	}


	public function get($id){
		$id = (int) $id;
		$q = $this->_db->query('SELECT * FROM meetings WHERE id ='.$id);
		$result = $q->fetch(PDO::FETCH_ASSOC);
		return new Meeting($result);
	}

	/*public function getList(){
		$list_meetings = array();
	    $q = $this->_db->query('SELECT * FROM meetings ORDER BY id');
	    while ($data = $q->fetch(PDO::FETCH_ASSOC))
	    {
	      $list_meetings[] = new Meeting($data);
	    }
	    return $list_meetings;
	}
	*/

	// Get a list of meetings created by an organizer and invited to.
	public function getListByOrg($id){
		$id = (int) $id;
		$meetings = array();
		$attendeesM = array();
		$q = $this->_db->query('SELECT * FROM meetings WHERE organizer_id ='.$id);
		while($result = $q->fetch(PDO::FETCH_ASSOC)){
			$meetings[] = new Meeting($result); 
		}
		$attendeesM = getListByAttendees();
		return $meetings;
	}

	// Get a list of meetings by attendees
	public function getListByAttendees($arrayIdMeetings){
		$meetings = array();
		foreach ($arrayIdMeetings as $value) {
			$meetings[] = get($value); 
		}
		return $meetings;
	}



}