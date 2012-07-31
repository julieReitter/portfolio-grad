<?php
 	require_once("../resources/connection.php");
	require_once("../resources/objects.php");
		/*
	session_start();

	if(!isset($_SESSION['user'])){
		header("Location: index.php");
	}
	
	$user = $_SESSION['user'];
	*/
	$workQuery = "SELECT * FROM work WHERE goody = 0 ORDER BY name DESC";
	$retrieveWork = mysql_query($workQuery);
	
	$queryGoodies = "SELECT * FROM work WHERE goody = 1 ORDER BY name DESC";
	$retrieveGoodies = mysql_query($queryGoodies);
	
	include('../header.php');
?>
	<section id="content">
		<h1 class="fl">Julie Reitter</h1>
		<a href="add.php" class="button add fr">Add New</a>
		<div class="clearfix"></div>
		<form id="set-order" action="order.php" method="post">
		<table id="overview">
			<thead>
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Edit</th>
					<th>Delete</th>
					<th>Order</th>
				</th>
			</thead>
			<tbody>
				<?php if(!empty($retrieveWork)): ?>
				<?php while( $row = mysql_fetch_assoc($retrieveWork) ) : ?>
					<tr>
						<td><?php echo date('m/d/Y' , strtotime($row['date'])); ?></td>
						<td><?php echo $row['name']; ?></td>
						<td><a href="add.php?edit=<?php echo $row['work_id'];?>"" class="edit">Edit</a></td>
						<td><a href="delete.php?delete=<?php echo $row['work_id'];?>" class="delete">Delete</a></td>
						<td><input type="text" name="wOrder[<?php echo $row['work_id'];?>]" value="<?php echo $row['order_value'];?>" style="width:30px;padding:0; margin: 0"/></td>
					</tr>
				<?php endwhile;?>
				<?php endif; ?>
				
				<tr class="goody-row">
					<td colspan="5"><h3>Goodies</h3></td>
				</tr>
				
				<?php if(!empty($retrieveWork)): ?>
				<?php while( $row = mysql_fetch_assoc($retrieveGoodies) ) : ?>
					<tr>
						<td><?php echo date('m/d/Y' , strtotime($row['date'])); ?></td>
						<td><?php echo $row['name']; ?></td>
						<td><a href="add.php?edit=<?php echo $row['work_id'];?>"" class="edit">Edit</a></td>
						<td><a href="delete.php?delete=<?php echo $row['work_id'];?>" class="delete">Delete</a></td>
						<td><input type="text" name="gOrder[<?php echo $row['work_id'];?>]"  value="<?php echo $row['order_value'];?>" style="width:30px;padding:0; margin:0"/></td>
					</tr>
				<?php endwhile;?>
				<?php endif; ?>
			</tbody>
		</table>
		<input type="submit" value="Update Order" class="fr"/>
		</form>
		<div class="clearfix"></div>

		<?php //include("company.php"); ?>
		
		<div class="clearfix"></div>
		<a href="format_json.php" class="button">Format All Json</a>
	</section>
	
	<script type="text/javascript">
		$("#skills").chosen();
		
		$(".delete").on("click", function(event){
			return confirm("Are you Sure You Want to Delete This Work");
		});
	</script>
</body>
</html>