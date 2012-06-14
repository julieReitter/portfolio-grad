<?php
###############################
#Funtions for Porfolio Site
###############################

function validateDate ($dateStr){
	if(!empty($dateStr)){
		$date_format = explode('/', $dateStr);
		if(is_numeric($date_format[0]) && is_numeric($date_format[1]) && is_numeric($date_format[2])){
			$check = checkdate($date_format[0], $date_format[1], $date_format[2]);
			$check = false ? $date = NULL : $date = "$date_format[2]-$date_format[0]-$date_format[1]";
		}else{
			$date = NULL;
		}
	}else{
		$date = NULL;	
	}
	
	return $date;
}

function uploadImage($name, $path){	
	$uploadError = true; 
	$fileName = $_FILES[$name]["name"];
	
	for($i=0; $i<count($fileName); $i++){
		$fileType = $_FILES[$name]['type'][$i];
		$fileError = $_FILES[$name]['error'][$i];
		$fileSize = $_FILES[$name]['size'][$i];
					
		if($fileType == 'image/jpeg' || $fileType == 'image/png'){
			if($fileSize < 	2000000){
				if($fileError > 0){
					$uploadError = false;
				}//File Error
			}else{
				$uploadError = false;	
			}// File Size
		}else{
			$uploadError = false;	
		}//File Type
		
		if($uploadError == true){
			move_uploaded_file($_FILES[$name]['tmp_name'][$i], $path . $_FILES[$name]["name"][$i]);
			//echo "Upload Successfull";
		}else{
			//echo "Upload Failed";
		}
	
	}//end loop

	return $uploadError;
}//close uploadImage

?>