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
	
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Julie Reitter Portfolio Backend | Add New</title>
</head>
<body>
	<form id="add-work" action="process.php" method="post" enctype="multipart/form-data">
		<div class="fl">
			<input type="hidden" name="postback" value="set"/>
			<input type="text" name="title" id="title" placeholder="title" required/>
			<span class="error"><?php if(isset($error["name"])) echo $error["name"]; ?></span>
			<input type="text" name="date" id="date" placeholder-"date" value="<?php echo $today; ?>" required/>
			<span class="error"><?php if(isset($error["date"])) echo $error["date"]; ?></span>
			<input type="text" name="order" id="order" placeholder="order"/><br/>
			<span class="error"><?php if(isset($error["order"])) echo $error["order"]; ?></span>
			<textarea name="description" id="description" required></textarea>
			<span class="error"><?php if(isset($error["description"])) echo $error["description"]; ?></span>
			<select name="skills[]" id="skills" multiple required>
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
			<span class="error"><?php if(isset($error["skills"])) echo $error["skills"]; ?></span>
			<?php $skillTitlesStr = implode("," , $skillTitles); ?>
			<input type="hidden" name="stitles" id="stitles" value="<?php echo $skillTitlesStr; ?>"/>
			<input type="checkbox" name="goody" id="goody" value="Goody"> Goody <br/>
		</div>
		<input type="file" name="thumbnail[]" id="thumbnail" value="thumbnail" required/>
		<input type="file" name="images[]" id="images" multiple value="images" required/>
		<input type="submit" value="Submit" />
	</form>	
</body>
</html>