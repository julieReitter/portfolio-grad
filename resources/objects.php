<?php
class Work{
	public $name = '';
	public $desciption = '';
	public $thumnail = '';
	public $user = 0;
	public $goody = false;
	public $date = '';
	public $orderVal = NULL;
	public $images = array();
		
	public function create() {
		//Insert Values ? 
		//Upload Images ?
		//
	}
	
	public function read() {
		
	}
	
}//close work


?>

<!--

$work = new Work();
$work.name = $_POST[name]
$work.description = $_POST[desc]
...
$work.create();

-->