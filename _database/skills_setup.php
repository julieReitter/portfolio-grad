<?php
require_once("../resources/connection.php");

//CAN ONLY ADD SKILLS TO THE BOTTOM OF LIST OR JSON WILL GET RUINED

$skills = array(
	"Featured",
	"HTML & CSS",
	"HTML5 & CSS3",
	"Javascript/Jquery",
	"jQuery Mobile",
	"Wordpress Theme Development",
	"UI / UX Design",
	"Responsive Design",
	"PhotoShop",
	"Illustrator",
	"PHP",
	"MySQL",
	"Python",
	"Flash/ActionScript",
	"Git"
);

$clear = "TRUNCATE TABLE skills";
mysql_query($clear);

foreach($skills as $skill){
	$querySkills = "INSERT INTO skills (skill_title) 
					VALUES ('$skill')";
	mysql_query($querySkills);
	echo $skill;
};

?>