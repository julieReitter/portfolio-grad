<?php
	$conn = mysql_connect("localhost", "root", "");
	if(!$conn) die ("Could Not Connect To Server");
	
	$db = mysql_select_db("portfolio", $conn);
	if(!$db) die ("Could Not Connect To Database");
?>