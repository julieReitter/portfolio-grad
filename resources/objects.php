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
	public $link = NULL;
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
		if(!validateImages('thumbnail')) $valid = false;
		if(!validateImages('images')) $valid = false;
		
		return $valid;	
	}//close validate
		
	public function create() {
		$names = explode(",", $this->skillNames);
		
		//Preparing string data for queries
		$this->name = mysql_real_escape_string($this->name);
		$this->description = mysql_real_escape_string($this->description);
		$this->link = mysql_real_escape_string($this->link);
		
		//Upload Images
		uploadImage('thumbnail', '../images/content/thumbnails/');
		uploadImage('images', '../images/content/');
		
		//Insert Work	
		$addQuery = "INSERT INTO work (name, description, thumbnail, goody, date, order_value, link)
					  VALUES ('$this->name', 
							  '$this->description', 
							  '$this->thumbnail', 
							  '$this->goody', 
							  '$this->dateCreated', 
							  '$this->orderVal',
							  '$this->link')";
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
	
	}//close create
	
	public function delete($id){
		
		$deleteWorkQuery = "DELETE FROM work WHERE work_id=$id";
		mysql_query($deleteWorkQuery);	
		$deleteWorkSkillsQuery = "DELETE FROM work_skills WHERE work_id=$id";
		mysql_query($deleteWorkSkillsQuery);
		
		//Remove data from skills json
		$jsonData = json_decode(file_get_contents('../js/skills.json'), true);
		foreach($jsonData as $key=>$val){
			unset($jsonData[$key]["$id"]);
		}
		file_put_contents("../js/skills.json", json_encode($jsonData));
		
		//Remove data from full work json
		$workJsonData = json_decode(file_get_contents('../js/works.json'), true);
		foreach($workJsonData as $key=>$val){
			unset($workJsonData[$key]["$id"]);
		}
		file_put_contents("../js/works.json", json_encode($workJsonData));
		
		//Remove data goodies json
		$goodiesJsonData = json_decode(file_get_contents('../js/goodies.json'), true);
		foreach($goodiesJsonData as $key=>$val){
			unset($goodiesJsonData[$key]["$id"]);
		}
		file_put_contents("../js/goodies.json", json_encode($goodiesJsonData));
				
	}
	
	public function createJson(){
		if(!$this->goody){
			//For work that is not a goody add to skills json
			$jsonData = json_decode(file_get_contents('../js/skills.json'), true);
			foreach($this->skills as $skill){
				$jsonData[$skill][$this->id] = array( "order" => $this->orderVal, 
													  "name" => $this->name, 
													  "thumbnail" => $this->thumbnail, 
													  "skills" => $this->skillTitles);
			}
			file_put_contents('../js/skills.json', json_encode($jsonData));
			
			//For work full details - not a goody - add to works json
			$workJsonData = json_decode(file_get_contents('../js/works.json'), true);
			$workJsonData[$this->id] = array("name" => $this->name, 
											 "desc" => $this->description, 
											 "images" => $this->images, 
											 "date" => $this->dateCreated, 
											 "order" => $this->orderVal,
											 "link" => $this->link);
			file_put_contents('../js/works.json', json_encode($workJsonData));
		
		}else{
			//For work that is a goody add to goodies json
			$jsonData = json_decode(file_get_contents('../js/goodies.json'), true);
			$jsonData[$this->id] = array("name" => $this->name, 
										 "thumbnail" => $this->thumbnail, 
										 "desc" => $this->description,
										 "link" => $this->link);
			file_put_contents('../js/goodies.json', json_encode($jsonData));
		}
	}//close createJson
	
		
}//close work

?>