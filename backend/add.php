<?php
	require_once("../resources/connection.php");
	require_once("../resources/functions.php");
	
	session_start();
	if(!isset($_SESSION['user'])){
		header("Location: index.php");
	}
	
	$user = $_SESSION['user'];
	
	$time = mktime(0, 0, 0, date("m"),date("d"),date("Y"));
	$today = date('m/d/Y', $time);
		
	$querySkills = "SELECT * FROM skills";
	$skillsResult = mysql_query($querySkills);
	$skillTitles = array();
	
	if(!empty($_POST['postback'])){
		$valid = true;
		$error = '';
		$name = $_POST['title'];
		$date = $_POST['date'];
		$description = $_POST['description'];
		$skills = $_POST['skills'];
		$skillNames = explode(",", $_POST['stitles']);
		$order = $_POST['order'];
		isset($_POST['goody']) ? $goody = true : $goody = false; 
		empty($_FILES['thumbnail']) ? $valid = false : $thumbnail = $_FILES['thumbnail']['name'][0]; 
		empty($_FILES['images']) ? $valid = false :  $images = $_FILES['images']['name'];
				
		//Date Validation
		$date = validateDate($date);
		
		//Validation
		if(empty($name)) {$valid = false; $error = "name";}
		if(empty($date) || $date == NULL) {$valid = false; $error = "date";}
		if(empty($description)) {$valid = false; $error = "desc";}
		if(empty($skills)) {$valid = false; $error = "skills";}
		if(!is_numeric($order)) {$valid = false; $error = "order";}
		
		if(!$valid){
			$error .= " Error";
		}else{		
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
	}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Julie Reitter Portfolio Backend | Add New</title>
</head>
<body>
	<form id="add-work" action="add.php" method="post" enctype="multipart/form-data">
		<span><?php if(isset($error)) echo $error; ?></span>
		<div class="fl">
			<input type="hidden" name="postback" value="set"/>
			<input type="text" name="title" id="title" placeholder="title"/>
			<input type="text" name="date" id="date" placeholder-"date" value="<?php echo $today; ?>"/>
			<input type="text" name="order" id="order" placeholder="order"/><br/>
			<textarea name="description" id="description"></textarea>
			<select name="skills[]" id="skills" multiple>
				<?php if(!empty($skillsResult)) : ?>
				<?php while( $row = mysql_fetch_assoc($skillsResult) ) : ?>
					<option value="<?php echo $row['skill_id']; ?>"> 
						<?php 
							echo $row['skill_title']; 
							$skillTitles[$row['skill_id']] = $row['skill_title'];
						?> 
					</option>			
				<?php endwhile; ?>
				<?php endif; ?>
			</select><br/>
			<?php $skillTitlesStr = implode("," , $skillTitles); ?>
			<input type="hidden" name="stitles" id="stitles" value="<?php echo $skillTitlesStr; ?>"/>
			<input type="checkbox" name="goody" id="goody" value="Goody"> Goody <br/>
		</div>
		<input type="file" name="thumbnail[]" id="thumbnail" value="thumbnail"/>
		<input type="file" name="images[]" id="images" multiple value="images"/>
		<input type="submit" value="Submit" />
	</form>	
</body>
</html>