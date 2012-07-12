<?php

require_once("connection.php");
require_once("functions.php");

class Work{
	public $id = NULL;
	public $name = 'untitled';
	public $description = '';
	public $thumbnail = NULL;
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
	
	public function get($id){
		$getQuery = "SELECT * FROM work WHERE work_id = '$id'";
		$getObj = mysql_fetch_assoc($getQuery);
		$this->id = $id;
		$this->name = $getObj['name'];
		$this->description = $getObj['description'];
	}
	
	public function validate(){
		$valid = true;
		$error = '';
		//Date Validation
		$this->dateCreated = validateDate($this->dateCreated);
		
		//General Validation
		if(empty($this->name)) {$valid = false; $error .= " name";}
		if(empty($this->dateCreated) || $this->dateCreated == NULL) {$valid = false; $error .= " date";}
		if(empty($this->description)) {$valid = false; $error .= " desc";}
		if(empty($this->skills)) {$valid = false; $error .= " skills";}
		if(!is_numeric($this->orderVal)) {$valid = false; $error .= " order";}
		//if($this->link == NULL ) {$valid = false; $error .= " link";} //&& !filter_var($this->link, FILTER_VALIDATE_URL)
		//validate - images (thumbs)
		if(isset($this->thumbnail) && !validateImages('thumbnail')) {
			$valid = false;
			$error .= ' thumbnail';
		}
		if(!empty($this->images) && !validateImages('images')){
			$valid = false;
			$error .= ' images';
		}
		
		echo $error;
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
		if($this->goody = false){
			uploadImage('images', '../images/content/');
		}else{
			uploadImage('images', '../images/content/goodies');
		}
		
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
	
	public function edit(){
		$editQuery = "UPDATE work SET
							 name = '$this->name', 
							 description = '$this->description',
							 goody = '$this->goody', 
							 date = '$this->dateCreated', 
							 order_value = '$this->orderVal',
							 link = '$this->link'
							 WHERE work_id = '$this->id'";
		
		echo $editQuery;
		
		$editResults = mysql_query($editQuery);
		if($editResults){
			 $skillsJsonData = json_decode(file_get_contents('../js/skills.json'), true);
			 foreach($skillsJsonData as $key=>$value){
				$this->thumbnail = $skillsJsonData[$key][$this->id]["thumbnail"];
				$this->skillTitles =  $skillsJsonData[$key][$this->id]["skills"];
				$skillsJsonData[$key][$this->id] = array( "order" => $this->orderVal, 
													  "name" => $this->name, 
													  "thumbnail" => $this->thumbnail, 
													  "skills" => $this->skillTitles);
			 }
			 file_put_contents('../js/skills.json', json_encode($skillsJsonData));
			//Remove data from full work json
			$workJsonData = json_decode(file_get_contents('../js/works.json'), true);
			$this->skillTitles =  $workJsonData[$this->id]["skills"];
			$workJsonData[$this->id] =  array("name" => $this->name, 
											 "desc" => $this->description, 
											 "images" => $this->images, 
											 "date" => $this->dateCreated, 
											 "skills" => $this->skillTitles,
											 "link" => $this->link);
			file_put_contents('../js/works.json', json_encode($workJsonData));			
		}
	}
	
	public function delete($id){
		$deleteWorkQuery = "DELETE w.*, ws.*, i.* FROM
								work w, work_skills ws, images i
								WHERE w.work_id = ws.work_id
								AND w.work_id = i.work_id
								AND w.work_id = $id";
		mysql_query($deleteWorkQuery);
		
		//Remove data from skills json
		$jsonData = json_decode(file_get_contents('../js/skills.json'), true);
		foreach($jsonData as $key=>$val){
			unset($jsonData[$key]["$id"]);
		}
		file_put_contents("../js/skills.json", json_encode($jsonData));
		
		//Remove data from full work json
		$workJsonData = json_decode(file_get_contents('../js/works.json'), true);
		unset($workJsonData[$id]);
		file_put_contents("../js/works.json", json_encode($workJsonData));				
	}
	
	public function createJson(){
		if(!$this->goody){
			//For work that is not a goody add to skills json
			$jsonData = json_decode(file_get_contents('../js/skills.json'), true);
			foreach($this->skillTitles as $skill){
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
											 "skills" => $this->skillTitles,
											 "link" => $this->link);
			file_put_contents('../js/works.json', json_encode($workJsonData));
		}
	}//close createJson
	
	public function editOrder($goody = false){
		$editOrderQuery = "UPDATE work SET order_value = '$this->orderVal' WHERE work_id = $this->id";
		mysql_query($editOrderQuery);

		if(!$goody){
			$jsonData = json_decode(file_get_contents('../js/skills.json'), true);
			foreach($jsonData as $skill=>$id){
				if(isset($jsonData[$skill][$this->id])){
					$jsonData[$skill][$this->id]['order'] = $this->orderVal;
				}
			}
			file_put_contents('../js/skills.json', json_encode($jsonData));
		}
		
	}
	
}//close work

?>