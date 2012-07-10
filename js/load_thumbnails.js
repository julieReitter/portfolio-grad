/*************************************
* Julie Reitter Portfolio 
* Copyright 2012

This file loads the thumbnails from the
skills selected in the search or 
featured images if search is empty
**************************************/
var j;
$(document).ready(function(){
	//Get Featured Work, Any work where skill not featured limit 8
	function loadFeaturedWork(){
		var featuredWork = [];
		$.getJSON('js/skills.json', function(json, skill){
			//Assumes featured work is id - 0;
			j = json;
		}).complete(function(){
			populateThumbnails(featuredWork);
		});
	}
	/***************************************
	* On Search Change (Chosen Trigger) 
	* clear #loader-section 
	* add work with skills selected.
	***************************************/ 
	function getWorkFromSkills(data){
		var selectedWork = [],
			workIds = [];
			
		$.getJSON('js/skills.json', function(json){
			$.each(data, function(index, key){
				$.each(json[key], function(id, value){
					if($.inArray(id, workIds) == -1){
						workIds.push(id);
						selectedWork.push(value);	
					}
				});
			});
		}).complete(function(){
			populateThumbnails(selectedWork, workIds);
		});
	}
	
	$("#skills-select").chosen().change(function(event){
		var data = $("#skills-select").val();
		if(data != null){ 
			getWorkFromSkills(data);
		}else{
			loadFeaturedWork();	
		}
	});
	
	function populateThumbnails(selectedWork, ids){
		var $loadSection = $("#loader-section"),
			workHtml = '<div id="full-details"></div> ';		
				
		$.each(selectedWork, function(key, value){
			var html = '', 
				skillsList = '';
			
			$.each(value.skills, function(index, title){
				skillsList += "<li>" + value.skills[index] + "</li>";	
			});
			
			html = '<div class="thumbnail">';
			html += '<a href="#" id="' + ids[key] + '">';
			html += '<span class="hover-overlay">';
			html += "<h2>" + value.name + "</h2>"
			html += "<ul>" + skillsList + "</ul>";
			html += '</span>';
			html += "<img src='images/content/thumbnails/" + value.thumbnail + "' alt='" + value.name + "'/>";
			html += '<a/></div>';
			
			workHtml += html;
		});
		
		
		//Remove all thumbnails that aren't relevant
		$loadSection.children().fadeOut().empty();
		
		//Load Contents into container
		$loadSection.html(workHtml).fadeIn();
			
		//Bind hover to thumbnails 
		$loadSection.find(".thumbnail a").hover(skillsHoverOverlay, hideOverlay).on("click", getWork);
	}
	
	//Onload triggers
	loadFeaturedWork();
	//Goody onload
	$(".goody a").hover(goodyHoverOverlay, hideGoodyOverlay);

	/***********************************
	* THUMBNAIL HOVER
	************************************/
	function skillsHoverOverlay(event){
		var $this = $(this),
			$overlay = $this.find(".hover-overlay");
			
		$overlay.slideDown("fast");
		$overlay.bind("mouseout", hideOverlay);
	}	
	
	function hideOverlay(event){
		var $this = $(this);
		$this.find(".hover-overlay").slideUp("fast");	
	}
	
	function getWork(event){
		var $this = $(this),
			$fullDetails = $("#full-details"),
			id = $this.attr("id");
		
		event.preventDefault();
		work(id).loadWorkDetails();

		if($fullDetails.html() != ""){
			$("body").scrollTop(300);
		}					
	}
	
	function goodyHoverOverlay(event){
		var $this = $(this),
			 $overlay = $this.find(".download-overlay");
		$overlay.slideDown('fast');
	}
	
	function hideGoodyOverlay(event){
		var $this = $(this);
		$this.closest('.goody').find('.download-overlay').slideUp('fast');
	}
	
	
});