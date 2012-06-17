<?php
	require_once("../resources/connection.php");
	require_once("../resources/functions.php");
	require_once("../resources/objects.php");
	
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
		$work = new Work();
		$work -> setProperty("name", $_POST['title']);
		$work -> setProperty("dateCreated", $_POST['date']);
		$work -> setProperty("description", $_POST['description']);
		if(isset($_POST['skills'])) $work -> setProperty("skills", $_POST['skills']);
		$work -> setProperty("skillNames", $_POST['stitles']);
		$work -> setProperty("orderVal", $_POST['order']);
		isset($_POST['goody']) ? $work -> setProperty('goody',true) : $goody = false; 
		empty($_FILES['thumbnail']) ? $valid = false : $work -> setProperty("thumbnail", $_FILES['thumbnail']['name'][0]); 
		empty($_FILES['images']) ? $valid = false :  $work -> setProperty("images", $_FILES['images']['name']);
		$valid = $work -> validate();
		
		if(!$valid){
			$error = "There was an error processing the form. Make sure all required fields are filled";
		}else{
			$work -> create();
			//var_dump($work);		
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
			<input type="text" name="title" id="title" placeholder="Title*" value="<?php if(isset($work)) echo $work -> name;?>"/>
			<input type="text" name="date" id="date" placeholder-"date" value="<?php if(isset($work)){ echo $work -> dateCreated; } else{ echo $today; }?>"/>
			<input type="text" name="order" id="order" placeholder="Order" value="<?php if(isset($work)) echo $work -> orderVal;?>"/><br/>
			<textarea name="description" id="description" placeholder="Description*"><?php if(isset($work)) echo $work -> description;?></textarea>
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
			<input type="checkbox" name="goody" id="goody" value="Goody" <?php if(isset($work) && $work->goody) echo "checked = checked";?> /> Goody <br/>
		</div>
		<input type="file" name="thumbnail[]" id="thumbnail" value="thumbnail"/>
		<input type="file" name="images[]" id="images" multiple value="images"/>
		<input type="submit" value="Submit" />
	</form>	
</body>
</html>