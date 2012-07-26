<?php
	include("../resources/connection.php");

	$getAllSkills = "SELECT s.skill_id AS 'sId', s.skill_title, w.*, ws.* FROM skills s, work_skills ws, work w
						WHERE s.skill_id = ws.skill_id
						AND ws.work_id = w.work_id
						AND w.goody = 0";
	$retrieveAllSkills = mysql_query($getAllSkills);
	
	$skillsJsonData = array();
	$worksJsonData = array();
	
	while( $row = mysql_fetch_assoc($retrieveAllSkills) ) {
		
		$queryRelativeSkills = "SELECT *  FROM skills s, work_skills ws, work w
										WHERE s.skill_id = ws.skill_id
										AND ws.work_id = w.work_id
										AND ws.work_id = " . $row['work_id'] .
										" AND s.skill_id <> 1";
		
		$retrieveRS = mysql_query($queryRelativeSkills);
		$rsTitles = array();
		while( $r = mysql_fetch_assoc($retrieveRS)){
			$rsTitles[] = $r['skill_title'];
		}
		
		$skillsJsonData[$row['skill_title']][$row['work_id']] = array(
			"order" => $row['order_value'],
			"name" => $row['name'],
			"thumbnail" => $row['thumbnail'],
			"skills" => $rsTitles
		);		
	}
	file_put_contents('../js/skills.json', json_encode($skillsJsonData));
		
	
	$getAllWork = "SELECT * FROM work WHERE goody = 0";
	$retrieveAllWork = mysql_query($getAllWork);
	
	while ( $row = mysql_fetch_assoc($retrieveAllWork) ) {
		$queryRelImages = "SELECT * FROM images WHERE work_id = " . $row['work_id'];
		$retrieveRelImages = mysql_query($queryRelImages);
		
		$queryRelSkills = "SELECT skill_title FROM skills s, work_skills ws
						WHERE ws.skill_id = s.skill_id
						AND ws.work_id = " . $row['work_id'] .
						" AND s.skill_id <> 1 ";
		$retrieveRelSkills = mysql_query($queryRelSkills);
		
		$relImages = array();
		$relSkills = array();
		
		while ( $i = mysql_fetch_assoc($retrieveRelImages) ){
			$relImages[] = $i['image_file'];
		}
		while( $s = mysql_fetch_assoc($retrieveRelSkills)){
			$relSkills[] = $s['skill_title'];
		}
		
		$worksJsonData[$row['work_id']] = array("name" => $row['name'], 
							 "desc" => $row['description'], 
							 "images" => $relImages, 
							 "date" => $row['date'], 
							 "skills" => $relSkills,
							 "link" => $row['link']);
	
	}
	file_put_contents('../js/works.json', json_encode($worksJsonData));
	
	echo "<h1>SKILLS</h1><pre>";
	print_r($skillsJsonData);
	echo "</pre>";
	
	echo "<h1>Work</h1><pre>";
	print_r($worksJsonData);
	echo "</pre>";

	
	
	
?>