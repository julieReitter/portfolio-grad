<?php
	require_once("../resources/connection.php");
	require_once("../resources/objects.php");

	if(isset($_POST['wOrder'])){
		$orderValues = $_POST['wOrder'];
		$work = new Work();
		foreach($orderValues as $key=>$val){
			$work->id = $key;
			$work->orderVal = $val;
			$work->editOrder();
		}
	}else{
		echo "Work Order Change Error";
	}
	
	if(isset($_POST['gOrder'])){
		$orderValues = $_POST['gOrder'];
		$work = new Work();
		foreach($orderValues as $key=>$val){
			$work->id = $key;
			$work->orderVal = $val;
			$work->editOrder();
		}
	}else{
		echo "Goody Order Change Error";
	}
	
	header("Location: overview.php");
?>