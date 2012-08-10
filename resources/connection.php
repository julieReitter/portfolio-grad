<?php
/*
	$conn = mysql_connect('juliereittercom.ipagemysql.com', 'jar125w', 'jar2663');
	if(!$conn) die ("Could Not Connect To Server");
	
	$db = mysql_select_db("jar_portfolio512", $conn);
	if(!$db) die ("Could Not Connect To Database");
*/

	//Local Testing
	$conn = mysql_connect("localhost", "root", "");
	if(!$conn) die ("Could Not Connect To Server");
	
	$db = mysql_select_db("portfolio", $conn);
	if(!$db) die ("Could Not Connect To Database");


?>