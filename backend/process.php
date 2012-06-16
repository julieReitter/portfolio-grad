<?php
	//General
	require_once("../resources/connection.php");
	require_once("../resources/functions.php");
	
	session_start();
	if(!isset($_SESSION['user'])){
		header("Location: index.php");
	}
	
	$user = $_SESSION['user'];
	
	//FORM PROCESSING 
	$valid = true;
	$error = array();
	$name = $_POST['title'];
	$date = $_POST['date'];
	$description = $_POST['description'];
	$skillNames = explode(",", $_POST['stitles']);
	$order = $_POST['order'];
	isset($_POST['skills']) ? $skills = $_POST['skills'] : $error['skills'] = 'Please select at least one skill';
	isset($_POST['goody']) ? $goody = true : $goody = false; 
	
	if(empty($_FILES['thumbnail']) || !validateImages('thumbnail')){
		 $valid = false;
		 $error['thumbnail'] = "Please Upload a Thumbnail (jpg/png)";
	}else{
		 $thumbnail = $_FILES['thumbnail']['name'][0];
	}
		  
	if(empty($_FILES['images']) || !validateImages('images')) {
		$valid = false;
		$error['thumbnail'] = "Please Upload at least One Image (jpg/png)";
	}else{
		$images = $_FILES['images']['name'];
	}
	
	//Date Validation
	$date = validateDate($date);
	
	//Validation
	if(empty($name)) {$valid = false; $error['name']="Please Enter A Title";}
	if(empty($date) || $date == NULL) {$valid = false; $error["date"] = "Please Enter A Date with the format MM/DD/YYYY";}
	if(empty($description)) {$valid = false; $error["description"] = "Please enter a description for the work";}
	if(!is_numeric($order)) {$valid = false; $error["order"] = "Order Must be a value";}
	
	if(!$valid){
		header("Location: add.php");
	}else{		
		//Insert Work	
		$addQuery = "INSERT INTO work (name, description, thumbnail, goody, date, order_value)
					  VALUES ('$name', '$description', '$thumbnail', '$goody', '$date', '$order')";
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
		
		//Process JSON
		if(!$goody){
			//For work that is not a goody add to skills json
			$jsonData = json_decode(file_get_contents('../js/skills.json'), true);
			foreach($skills as $skill){
				$jsonData[$skill][] = array($workId => array( $order, $name, $thumbnail, $skillTitles));
			}
			file_put_contents('../js/skills.json', json_encode($jsonData));
			
			//For work full details - not a goody - add to works json
			$workJsonData = json_decode(file_get_contents('../js/works.json'), true);
			$workJsonData[$workId][] = array($name, $description, $images, $date, $order);
			file_put_contents('../js/works.json', json_encode($workJsonData));
		}else{
			//For work that is a goody add to goodies json
			$jsonData = json_decode(file_get_contents('../js/goodies.json'), true);
			$jsonData[$workId][] = array($name, $thumbnail, $description);
			file_put_contents('../js/goodies.json', json_encode($jsonData));
		}
					
		header("Location: overview.php");
	}

?>