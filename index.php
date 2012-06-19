<?php include('header.php'); ?>

<section id="content">
	
	<h1 id="tagline">web design and development that combines
		<br/><span class="fr"> the logical with the beautiful</span></h1>
	<div class="clearfix"></div>
	<select id="skills-select" multiple data-placeholder="Search a skill to view related work">
		<option>HTML</option>
		<option>CSS</option>
		<option>JavaScript</option>
		<option>Photoshop</option>
	</select>
	
	<div id="loader-area">
		<div class="thumbnail">
			<span class="hover-overlay"></span>
			<img/>
		</div>
	</div>
	
</section><!--close content-->	

<?php include('footer.php'); ?>
<script type="text/javascript">
	$("#skills-select").chosen();
</script>