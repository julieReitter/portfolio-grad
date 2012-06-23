/*************************************
* Julie Reitter Portfolio 
* Copyright 2012
**************************************/

$(document).ready(function(){
	//Get Featured Work, Any work where order < 8
	
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
			populateThumbnails(selectedWork);
		});
	}
	
	$("#skills-select").chosen().change(function(event){
		var data = $("#skills-select").val();
		if(data != null) 
			getWorkFromSkills(data);
	});
	
	function populateThumbnails(selectedWork){
		console.log("Loading");
		var $loadSection = $("#loader-section"),
			workHtml = '';		
				
		$.each(selectedWork, function(key, value){
			var html = '', 
				skillsList = '';
			
			$.each(value.skills, function(index, title){
				skillsList += "<li>" + value.skills[index] + "</li>";	
			});
			
			html = '<div class="thumbnail">';
			html += '<span class="hover-overlay">';
			html += "<h2>" + value.name + "</h2>"
			html += "<ul>" + skillsList + "</ul>";
			html += '</span>';
			html += "<img src='images/content/thumbnails/" + value.thumbnail + "' alt='" + value.name + "'/>";
			html += '</div>';
			
			workHtml += html;
		});
		
		/******************************
		* Remove all thumbnails that aren't relevant
		******************************/
		$loadSection.children().fadeOut().empty();
		
		
		//Load Contents into container
		$loadSection.html(workHtml);
			
	}
	
});