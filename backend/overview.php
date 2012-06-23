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

	include('../header.php');
?>
	<section id="content">
		<h1 class="fl">Julie Reitter</h1>
		<a href="add.php" class="button add fr">Add New</a>
		<div class="clearfix"></div>
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
						<td><a href="#" class="edit">Edit</a></td>
						<td><a href="delete.php?delete=<?php echo $row['work_id'];?>" class="delete">Delete</a></td>
					</tr>
				<?php endwhile;?>
				<?php endif; ?>
			</tbody>
		</table>
	</section>
</body>
</html>