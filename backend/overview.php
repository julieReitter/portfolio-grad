<?php
 	require_once("../resources/connection.php");
	require_once("../resources/objects.php");
	
	session_start();
	if(!isset($_SESSION['user'])){
		header("Location: index.php");
	}
	
	$user = $_SESSION['user'];
	
	$query_work = "SELECT * FROM work ORDER BY date DESC";
	$work_results = mysql_query($query_work); 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Julie Reitter Portfolio Backend</title>
</head>
<body>
	<section id="content">
		<h1>Julie Reitter</h1>
		<a href="add.php" class="button add">Add New</a>
		<table id="overview">
			<thead>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Edit</th>
					<th>Delete</th>
				</th>
			</thead>
			<tbody>
				<?php if(!empty($work_results)): ?>
				<?php while( $row = mysql_fetch_assoc($work_results) ) : ?>
					<tr>
						<td><?php echo date('m/d/Y' , strtotime($row['date'])); ?></td>
						<td><?php echo $row['name']; ?></td>
						<td><a href="#" class="button edit">Edit</a></td>
						<td><a href="delete.php?delete=<?php echo $row['work_id'];?>" class="button delete">Delete</a></td>
					</tr>
				<?php endwhile;?>
				<?php endif; ?>
			</tbody>
		</table>
	</section>
</body>
</html>