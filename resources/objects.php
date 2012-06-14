<?php

require_once("connection.php");
require_once("functions.php");

class Work{
	private $id;
	public $name = 'untitled';
	public $description = 'item work';
	public $thumbnail = 'unknown.jpg';
	public $goody = false;
	public $dateCreated = '00/00/0000';
	public $orderVal = NULL;
	public $images = array();
	public $skills = array();
	public $skillNames;
	private $skillTitles = array();
	
	public function setProperty($prop, $val){
		$this->$prop = $val;	
	}//close set prop
	
	public function validate(){
		$valid = true;
		
		//Date Validation
		$this->dateCreated = validateDate($this->dateCreated);
		
		//General Validation
		if(empty($this->name)) {$valid = false; $error = "name";}
		if(empty($this->dateCreated) || $this->dateCreated == NULL) {$valid = false; $error = "date";}
		if(empty($this->description)) {$valid = false; $error = "desc";}
		if(empty($this->skills)) {$valid = false; $error = "skills";}
		if(!is_numeric($this->orderVal)) {$valid = false; $error = "order";}
		
		//validate - images (thumbs)
		
		return $valid;	
	}//close validate
		
	public function create() {
		$names = explode(",", $this->skillNames);
		
		//Preparing data for queries
		$this->name = mysql_real_escape_string($this->name);
		$this->description = mysql_real_escape_string($this->description);
		
		//Upload & Validate Images
		$thumb = uploadImage('thumbnail', '../images/content/thumbnails/');
		$imgs = uploadImage('images', '../images/content/');
		
		if($thumb || $imgs){ 
			//Insert Work	
			$addQuery = "INSERT INTO work (name, description, thumbnail, goody, date, order_value)
						  VALUES ('$this->name', 
								  '$this->description', 
								  '$this->thumbnail', 
								  '$this->goody', 
								  '$this->dateCreated', 
								  '$this->orderVal')";
			$results = mysql_query($addQuery);
			
			//Join Skill and Work 
			$this->id = mysql_insert_id();
			foreach($this->skills as $skill){
				$q = "INSERT INTO work_skills VALUES ($this->id, $skill)";
				mysql_query($q);
				$this->skillTitles[] = $names[$skill-1];
			}
				
			//Insert Images
			foreach($this->images as $image){
				$imageQuery = "INSERT INTO images (image_file, work_id)
								VALUES ('$image', '$this->id')";
				mysql_query($imageQuery);
			}
			
			$this->createJson();
		}
	
	}//close create
	
	public function createJson(){
		if(!$this->goody){
			//For work that is not a goody add to skills json
			$jsonData = json_decode(file_get_contents('../js/skills.json'), true);
			foreach($this->skills as $skill){
				$jsonData[$skill][] = array($this->id => array( $this->orderVal, $this->name, $this->thumbnail, $this->skillTitles));
			}
			file_put_contents('../js/skills.json', json_encode($jsonData));
			
			//For work full details - not a goody - add to works json
			$workJsonData = json_decode(file_get_contents('../js/works.json'), true);
			$workJsonData[$this->id][] = array($this->name, $this->description, $this->images, $this->dateCreated, $this->orderVal);
			file_put_contents('../js/works.json', json_encode($workJsonData));
		
		}else{
			//For work that is a goody add to goodies json
			$jsonData = json_decode(file_get_contents('../js/goodies.json'), true);
			$jsonData[$this->id][] = array($this->name, $this->thumbnail, $this->description);
			file_put_contents('../js/goodies.json', json_encode($jsonData));
		}
	}//close createJson
		
}//close work


?>