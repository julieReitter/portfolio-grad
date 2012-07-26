<?php
	$querySkills = "SELECT * FROM skills";
	$skillsResult = mysql_query($querySkills);

	
	if(!empty($_POST['postback'])){
		$valid = true;
		$name = $_POST['company-name'];
		
		if(isset($_POST['skills'])) $skills = $_POST['skills'];
		if(empty($name)) $valid = false;
		
		$skillStr = implode("-", $skills);
		
		if($valid){
			$insertCompany = "INSERT INTO companies (company_name, skill_id)
									VALUES ('$name', '$skillStr')";
			mysql_query($insertCompany);
		}
		
	}

?>



<form name="new-company" method="post" action="overview.php">
	<h2>Company Setups</h2>
	<input type="hidden" name="postback" value="set" />
	<input type="text" name="company-name" placeholder="company name"/>
	<select id="skills" name="skills[]" multiple>
		<?php while( $row = mysql_fetch_assoc($skillsResult) ) : ?>
			<option value="<?php echo $row['skill_id']; ?>" <?php if(isset($selectedIds) && in_array($row['skill_id'], $selectedIds)) echo "selected='selected'";?>> 
				<?php 
					echo $row['skill_title']; 
				?> 
			</option>			
		<?php endwhile; ?>
	</select><br/>
	<input type="submit" value="Create" />
</form>
