<?php
require_once("../resources/connection.php");
require_once("../resources/objects.php");

	if(isset($_GET['delete'])){
		$w = new Work();
		$w->delete($_GET['delete']);
		header("Location: overview.php");
	}
?>