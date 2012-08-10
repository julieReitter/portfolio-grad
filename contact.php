<?php
//session_start();

$errors = array();
$postback = array();
isset($_GET['sent']) ? $sent = $_GET['sent'] : $sent = false;

//Get postback data
if(isset($_SESSION['name'])) $postback['name'] = $_SESSION['name'];
if(isset($_SESSION['email'])) $postback['email'] = $_SESSION['email'];
if(isset($_SESSION['message']))	$postback['message'] = $_SESSION['message'];
if(isset($_SESSION['website']))	$postback['website'] = $_SESSION['website'];

if(isset($_GET['error'])){
	$error = explode("," , $_GET['error']);		
	foreach($error as $err){
		switch($err){
			case "email":
				$errors['email'] = "Please Enter A Valid Email Address";
				break;
			case "name":
				$errors['name'] = "Please Enter Your Name";
				break;
			case "message":
				$errors['message'] = "Please Enter A Message";	
				break;
			default:
				break;
		}//close switch
	}//close loop
}//close error check

if($sent == false){
include('header.php');
?>
<section id="content" class="contact-contain">
	<form id="contact" method="post" action="resources/sendform.php">
		<input type="hidden" name="postback" value="set"/>
		<input type="text" name="name" id="name" placeholder="Your Name*" value ="<?php if(isset($postback['name'])) echo $postback['name'];?>" required="required" />
		<span class="error"><?php if(isset($errors['name'])) echo $errors['name'];?></span>
		<input type="text" name="email" id="email" placeholder="Your Email*"  value ="<?php if(isset($postback['email'])) echo $postback['email'];?>" required="required"/>
		<span class="error"><?php if(isset($errors['email'])) echo $errors['email'];?></span>
		<input type="text" name="website" id="website" placeholder="You Website" value ="<?php if(isset($postback['website'])) echo $postback['website'];?>"/>
		<textarea name="message" id="message" required="required"><?php if(isset($postback['message'])) echo $postback['message']; else echo "Your Message*";?></textarea>
		<span class="error"><?php if(isset($errors['message'])) echo $errors['message'];?></span>
		<input type="submit" value="Send"/>
	</form>
	
	<aside>
		<h1>Get in touch</h1>
		<p>Fill out the form to the left <br/>or call 973.615.3429.</p>
		<p>I'm currently avaiable <br/>for work.</p>	
	</aside>
<div class="push"></div>
</section>
<?php 
}else{
include('header.php');
?>

<section id="content">
	<h1>Thank you for contacting me</h1>
	<h3>I will respond to your message as soon as possible</h3>
<div class="push"></div>
</section>
<?php	
}//close sent check
?>

<?php include('footer.php'); ?>