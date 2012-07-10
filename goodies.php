<?php include('resources/connection.php');?>
<?php include('header.php'); ?>
<?php
	$goodyQuery = "SELECT * FROM work WHERE goody=1 ";
	$retrieveGoodies = mysql_query($goodyQuery);
?>

<section id="content">
	<?php while($row = mysql_fetch_assoc($retrieveGoodies)): ?>
		<div class="goody">
			<a href="<?php $row['link']; ?>" class="image-link">
			<div class="download-overlay">Download Zip</div>
			<img src="images/content/<?php echo $row['thumbnail'];?>" alt="<?php $row['name'];?>"  />
			</a>
			<div class="info">
				<h2>
					<a href="?goody=<?php echo $row['work_id'];?>">
						<?php echo $row['name']; ?>
					</a>
				</h2>
				<p>
					<?php
						echo $row['description'];
					?>
				</p>
				<!--<a href="?goody=<?php echo $row['work_id'];?>">Read More</php>-->
			</div>
		</div>
	<?php endwhile; ?>
</section>


<?php include('footer.php'); ?>