<?php

require_once("connection.php");
require_once("functions.php");

class Work{
	public $name = 'untitled';
	public $desciption = 'item work';
	public $thumnail = 'unknown.jpg';
	public $user = 0;
	public $goody = false;
	public $date = '00/00/0000';
	public $orderVal = NULL;
	public $images = array();
	public $skills = array();
	
	public function setProperty($prop, $val){
		$this->$prop = $val;	
	}
		
	public function create() {
		//Insert Work	
		$addQuery = "INSERT INTO work (name, description, thumbnail, user_id, goody, date, order_value)
					  VALUES ('$name', '$description', '$thumbnail', '$user', '$goody', '$date', '$order')";
		$results = mysql_query($addQuery);
		
		//Join Skill and Work 
		$workId = mysql_insert_id();
		foreach($skills as $skill){
			$q = "INSERT INTO work_skills VALUES ($workId, $skill)";
			mysql_query($q);
			$skillTitles[] = $skillNames[$skill-1];	
		}
		
		//Insert/Upload Images
		uploadImage('thumbnail', '../images/content/thumbnails/');
		uploadImage('images', '../images/content/');
		
		foreach($images as $image){
			$imageQuery = "INSERT INTO images (image_file, work_id)
							VALUES ('$image', '$workId')";
			mysql_query($imageQuery);
		}
	}
	
	public function read() {
		
	}
	
}//close work


?>

<!--

$work = new Work();
$work.name = $_POST[name]
$work.description = $_POST[desc]
...
$work.create();

-->