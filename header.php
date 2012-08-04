<?php
	session_start();
	
	define('ROOT', 'http://localhost/portfolio');
	//define('ROOT', 'http://www.juliereitter.com/portfoliograd');
	if(!isset($_SESSION['user'])){
		header("Location:" . ROOT . "/backend/index.php");
	}
	$user = $_SESSION['user'];
?>

<!DOCTYPE HTML>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Julie Reitter | Boston Web Design &amp; Development</title>
<!--Styles -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT . "/resources/lib/chosen.css";?>" />
<link rel="stylesheet/less" type="text/css" href="<?php echo ROOT . "/css/main.less";?>"/>
<!--Fonts-->
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<!-- Script Libraries -->
<!--[if lt IE 9]>
<script src='<?php echo ROOT . "/resources/lib/iehtml5.js"; ?>' type="text/javascript"></script>
<![endif]-->
<script src='<?php echo ROOT . "/resources/lib/jquery-1.7.2.min.js"; ?>' type="text/javascript"></script>
<script src='<?php echo ROOT . "/resources/lib/less-1.3.0.min.js"; ?>' type="text/javascript"></script>
<script src='<?php echo ROOT . "/resources/lib/chosen.jquery.min.js"; ?>' type="text/javascript"></script>
<script src='<?php echo ROOT . "/resources/lib/imagesloaded.js"; ?>' type="text/javascript"></script>
<!-- My Scripts -->
<script src='<?php echo ROOT . "/js/load_thumbnails.js";?>' type="text/javascript"></script>
<script src='<?php echo ROOT . "/js/full_work.js";?>' type="text/javascript"></script>
</head>

<body>
	<header>
		<div class="wrapper">
			<nav>
				<ul>
					<li><a href='<?php echo ROOT . "/index.php";?>'>home</a></li>
					<li><a href='<?php echo ROOT . "/about.php";?>'>about</a></li>
					<li id="logo"><a href='<?php echo ROOT . "/index.php";?>'></a></li>
					<li><a href='<?php echo ROOT . "/goodies.php";?>'>goodies</a></li>
					<li><a href='<?php echo ROOT . "/contact.php";?>'>contact</a></li>
				</ul>
			</nav>
		</div>
	</header>
