<?php
require_once("../resources/connection.php");

//CAN ONLY ADD SKILLS TO THE BOTTOM OF LIST OR JSON WILL GET RUINED

$skills = array(
	"Featured",
	"HTML",
	"HTML5",
	"CSS",
	"CSS3",
	"PHP",
	"MySQL",
	"PhotoShop",
	"Illustrator",
	"Javascript/Jquery",
	"jQuery Mobile",
	"Wordpress Theme Development",
	"Python",
	"Flash/ActionScript"
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