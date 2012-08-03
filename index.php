<?php include('header.php'); ?>
<?php include('resources/connection.php');?>

<section id="content">
	
	<h1 class="tagline">Hello, I'm Julie and I'm a web designer and front-end developer.
		<br/> I love clean code as much as I love clean design 
		<!--<br/><span class="fr"> the logical with the beautiful</span>-->
	</h1>
	<div class="clearfix"></div>
	<select id="skills-select" multiple data-placeholder="Search a skill to view related work" style="width:550px;">
	<?php
		$querySkills = "SELECT * FROM skills WHERE skill_title <> 'featured'";
		$skillsResult = mysql_query($querySkills);
		
		while($row = mysql_fetch_array($skillsResult)){
			echo "<option value='" . $row['skill_title'] . "'>";
			echo $row['skill_title'];
			echo "<option>";
		}
	?>
	</select>
		
	<div id="loader-section">
		<div id="full-details"></div>
		<div class="clearfix"></div>
		<div id="thumbnails"></div>
	</div>
	
</section><!--close content-->	

<?php include('footer.php'); ?>
<script type="text/javascript">
	$("#skills-select").chosen();
</script>