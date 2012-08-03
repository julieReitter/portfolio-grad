/*************************************
* Julie Reitter Portfolio 
* Copyright 2012

This file loads the full details about
the selected work.
**************************************/
var minHeight;

var work = function(id){
	var workDetails,
		 skillsList;
	
	var getDetailsFromWork = function(){
		$.getJSON ("js/works.json", id, function(json){
			workDetails = json[id];
		}).complete(function(){
			populateWorkDetails(workDetails);
		});
	};
	
	var populateWorkDetails = function(workObj){
		var $fullDetailsSection = $("#full-details"),
			images = '',
			skillsList = '',
			html = '',
			emptyDetails;
			
		$.each(workObj.images, function(key, value){
			images += "<li><img src='images/content/" + value + "' alt='" + workObj.name + "'></li>";	
		});
		
		$.each(workObj.skills, function(index){
			skillsList += "<li>" + workObj.skills[index] + "</li>";
		});
		
		html += '<div id="gallery">';
		html += '<ul>';
		html += images;
		html += '</ul></div>';
		html += '<div id="details">';
		html += '<h2>' + workObj.name + ' <a href="#" class="fr close">X</a></h2>';
		html += '<p>' + workObj.desc + '</p>';
		html += "<h3>Skills Used</h3><ul class='item-skills'>" + skillsList + "</ul>";
		if(workObj.link != ""){
			html += '<a href="' + workObj.link + '" class="button" target="_blank">Visit Site</a>';	
		}
		html += '</div>';
		
		if ($fullDetailsSection.html() == '') {
			$("#" + id + " .hover-overlay").show();
			$fullDetailsSection.html(html).find("img").imagesLoaded(function(){
				$fullDetailsSection.slideDown(function(){
					$(".thumbnail .hover-overlay").removeClass("blue-loader").hide();
				});
				$(".close").click(closeFullDetails);
			});
		} else {
			$fullDetailsSection.fadeOut(function(){
				$fullDetailsSection.html(html).find("img").hide();
				$("#gallery").addClass("gray-loader");
				$fullDetailsSection.fadeIn("slow", function(){
					var $this = $(this);
					$this.find("img").imagesLoaded(function(){
						$("#gallery").removeClass("gray-loader");
						$this.find("img").fadeIn("slow");
					});
				});
				$(".close").click(closeFullDetails); 	
			});
		}
		
		//$fullDetailsSection.after("<div class='clearfix'></div>");
	};
	
	var closeFullDetails = function(event){
		var $fullDetailsSection = $("#full-details");
		event.preventDefault();
				
		$fullDetailsSection.slideUp( function(){
			$fullDetailsSection.html('');
		});
	};
	
	return {
		loadWorkDetails : function(){
			getDetailsFromWork(id);
		}
	};
	
};