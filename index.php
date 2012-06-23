<?php include('header.php'); ?>
<?php include('resources/connection.php');?>

<section id="content">
	
	<h1 id="tagline">web design and development that combines
		<br/><span class="fr"> the logical with the beautiful</span></h1>
	<div class="clearfix"></div>
	<select id="skills-select" multiple data-placeholder="Search a skill to view related work">
	<?php
		$querySkills = "SELECT * FROM skills";
		$skillsResult = mysql_query($querySkills);
		
		while($row = mysql_fetch_array($skillsResult)){
			echo "<option value='" . $row['skill_id'] . "'>";
			echo $row['skill_title'];
			echo "<option>";
		}
	?>
	</select>
		
	<div id="loader-section">
		<div class="thumbnail">
			<span class="hover-overlay"></span>
			<img/>
		</div>
	</div>
	
</section><!--close content-->	

<?php include('footer.php'); ?>
<script type="text/javascript">
	$("#skills-select").chosen();
</script>