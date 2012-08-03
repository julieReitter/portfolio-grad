/*************************************
* Julie Reitter Portfolio 
* Copyright 2012

This file loads the thumbnails from the
skills selected in the search or 
featured images if search is empty
**************************************/

$(document).ready(function(){
	var loadedWork = []; //Array of work objects - from skills.json
	var currentId; //Index of full work item TODO - incorporate in full_work.js?
	
	//Get Featured Work, Any work where skill not featured limit 8
	function loadFeaturedWork(){
		var featuredWork = [],
			 workIds = [];
			 
		$.getJSON('js/skills.json', function(json){
			//Assumes featured work is id - 1;
			$.each(json.Featured, function(id, value){
				workIds.push(id);
				featuredWork.push(value);
			});
		}).complete(function(){
			$.map(featuredWork, function(val, i){
				val.id = workIds[i];
			});
			populateThumbnails(featuredWork);
			loadedWork = featuredWork;
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
			$.map(selectedWork, function(val, i){
				val.id = workIds[i];
			});			
			populateThumbnails(selectedWork);
			loadedWork = selectedWork;
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
	
	function populateThumbnails(selectedWork){
		var $loadSection = $("#loader-section"),
			workHtml = '<div id="full-details"></div><div class="clearfix"></div>';		
		
		selectedWork.sort( function(a, b){
			return a.order - b.order;	
		});
		
		$.each(selectedWork, function(key, value){
			var html = '', 
				skillsList = '';
			
			$.each(value.skills, function(index, title){
				skillsList += "<li>" + value.skills[index] + "</li>";	
			});
					
			html = '<div class="thumbnail">';
			html += '<a href="#" id="' + value.id + '">';
			html += '<span class="hover-overlay">';
			html += "<h2>" + value.name + "</h2>"
			html += "<ul>" + skillsList + "</ul>";
			html += '</span>';
			html += "<img src='images/content/thumbnails/" + value.thumbnail + "' alt='" + value.name + "'/>";
			html += '<a/></div>';
			
			workHtml += html;
		});
		
		workHtml += " <div class='clearfix'></div>";
		
		//Remove all thumbnails that aren't relevant
		$loadSection.fadeOut("slow", function() {
			$loadSection.children().empty();
			//Load Contents into container
			$loadSection.html(workHtml).fadeIn();
			//Bind hover to thumbnails 
			$loadSection.find(".thumbnail a").hover(skillsHoverOverlay, hideOverlay).on("click", getWork);
		});
		
			
		//Bind hover to thumbnails 
		//$loadSection.find(".thumbnail a").hover(skillsHoverOverlay, hideOverlay).on("click", getWork);
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
			
		$overlay.fadeIn(300, "swing");
		$overlay.bind("mouseout", hideOverlay);
	}	
	
	function hideOverlay(event){
		var $this = $(this);
		$this.find(".hover-overlay").fadeOut(300, "swing");	
	}
	
	function getWork(event){
		var $this = $(this),
			$fullDetails = $("#full-details"),
			id = $this.attr("id");
		
		event.preventDefault();
		
		//PRELOADER
		$this.find(".hover-overlay").addClass("blue-loader");
		
		work(id).loadWorkDetails();
		currentIndex = getIndexById(loadedWork, id);
		
		if($fullDetails.html() != ""){
			$("body").scrollTop(300);
		}

		$(document).unbind("keydown"); //unbinds previous event to prevent skipping
		$(document).on("keydown", nextWork);	
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
	
	function nextWork(event){
		var key = event.keyCode,
			 right = 39,
			 left = 37,
			 len = loadedWork.length,
			 index,
			 next,
			 prev;
		
		if(key == right && (currentIndex < (len-1)) ){
			next = loadedWork[currentIndex + 1];
			work(next.id).loadWorkDetails();
			currentIndex++;
		}
		if(key == left && (currentIndex > 0)){
			prev = loadedWork[currentIndex - 1];
			work(prev.id).loadWorkDetails();
			currentIndex--;
		}
	}
	
	function getIndexById(arr, id) {
		var len = arr.length,
			 index;
		for(i=0; i<len; i++){
			if(loadedWork[i]['id'] === id){
				index = i;
				break;
			}
		}
		return index;
	}

	$('[placeholder]').focus(function() {
	var input = $(this);
	if (input.val() == input.attr('placeholder')) {
	  input.val('');
	  input.removeClass('placeholder');
	}
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
		 input.addClass('placeholder');
		 input.val(input.attr('placeholder'));
	  }
	}).blur();
	
	
	
	
});