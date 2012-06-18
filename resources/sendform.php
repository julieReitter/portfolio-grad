<?php

function spamcheck($field){
  //filter_var() sanitizes the e-mail
  //address using FILTER_SANITIZE_EMAIL
  $field=filter_var($field, FILTER_SANITIZE_EMAIL);

  //filter_var() validates the e-mail
  //address using FILTER_VALIDATE_EMAIL
  if(filter_var($field, FILTER_VALIDATE_EMAIL)){
    return TRUE;
  }else{
    return FALSE;
  }
}
 
if (!empty($_POST['postback'])){
	session_start();
	
	$valid = true;
	$error = '';
	
	if(empty($_POST['email'])){
		$valid = false;
		$error .= "email,";	
	}
	if(empty($_POST['name'])){
		$valid = false; 	
		$error .= "name,";
	}
	if(empty($_POST['message'])){
		$valid = false;
		$error .= "message,";
	}
	
	$mailcheck = spamcheck($_POST['email']);

	if ($mailcheck==true && $valid==true){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$website = $_POST['website'];
		$comments = $_POST['message'];
			
		// Build the email
		$to = 'julie.reitter@gmail.com';
		$subject = "$name $website - Contact Form";
		$message = " Name: $name \n Email: $email \n Website: $website \n\n Message: \n $comments";
		$headers = " ";
		
		// Send the mail using PHPs mail() function
		mail($to, $subject, $message, $headers);
		
		session_destroy();
		// Redirect
		header("Location: ../contact.php?sent=true");	
	}//close
	else{
		if(isset($_POST['email'])) 
			$_SESSION['email'] = $_POST['email'];
		if(isset($_POST['message']))
			$_SESSION['message'] = $_POST['message'];
		if(isset($_POST['name']))
			$_SESSION['name'] = $_POST['name'];
		if(isset($_POST['website']))
			$_SESSION['website'] = $_POST['website'];
			
		header("Location: ../contact.php?error=$error");	
	}

}//close postback check

?>