<?php
require_once("../resources/connection.php");
require_once("../resources/objects.php");

if(isset($_GET['id']) && is_numeric($_GET['id'])){
	$work = new Work();
	$work -> setProperty("id", mysql_real_escape_string($_GET['id']));
	$work -> setProperty("name", $_POST['title']);
	$work -> setProperty("dateCreated", $_POST['date']);
	$work -> setProperty("description", $_POST['description']);
	if(isset($_POST['skills'])) $work -> setProperty("skills", $_POST['skills']);
	$work -> setProperty("skillNames", $_POST['stitles']);
	$work -> setProperty("orderVal", $_POST['order']);
	if(!empty($_POST['link'])) $work -> setProperty('link', $_POST['link']);
	isset($_POST['goody']) ? $work -> setProperty('goody',true) : $goody = false;
	
	$valid = $work -> validate();
	
	if($valid){
		$work->edit();
	}
	
}

?>