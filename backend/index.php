<?php
	require('../resources/connection.php');
	$error;
	
	if(!empty($_POST['postback'])){
		$un = $_POST['username'];
		$pass = $_POST['password'];
		
		$query = "SELECT * FROM user WHERE username='$un'";
		$results = mysql_query($query);
		
		$valid = false;

		while($row = mysql_fetch_assoc($results)){
			if ($row['username'] == $un && $row['password'] == md5($pass)) {
				$valid = true;
				session_start();
				$_SESSION['user'] = $row['user_id'];
				header("Location: overview.php");
			} else {
				$error = "Your Login Information Was Invalid";	
			}
		}
	}
	
	include('../header.php');
?>
	<section id="content" class="login">
		<form id="login" action="index.php" method="post">
			<span class="error"><?php if(isset($error)) echo $error; ?></span>
			<input type="hidden" name="postback" value="set"/>
			<input type="text" name="username" id="username" placeholder="Username"/>
			<input type="password" name="password" id="password" placeholder="Password"/>
			<input type="submit" value="Login"/>
		</form>
	</section>
</body>
</html>