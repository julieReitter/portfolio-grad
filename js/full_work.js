/*************************************
* Julie Reitter Portfolio 
* Copyright 2012

This file loads the full details about
the selected work.
**************************************/

var work = function(id){
	var workDetails;
	var skillsList;
	
	var getDetailsFromWork = function(){
		workPage = $.getJSON ("js/works.json", id, function(json){
			workDetails = json[id];
		}).complete(function(){
			populateWorkDetails(workDetails);
		});
	};
	
	var populateWorkDetails = function(workObj){
		var $fullDetailsSection = $("#full-details"),
			images = '',
			skillsList = '',
			html = '';
			
		$.each(workObj.images, function(key, value){
			images += "<li><img src='images/content/" + value + "' alt='" + workObj.name + "'></li>";	
		});
		
		html += '<div id="gallery">';
		html += '<ul>';
		html += images;
		html += '</ul></div>';
		html += '<div id="details">';
		html += '<h2>' + workObj.name + '</h2>';
		html += '<p>' + workObj.desc + '</p>';
		html += '<a href="' + workObj.link + '" class="button" target="_blank">Visit Site</a>';
		html += '</div>';
		
		$fullDetailsSection.html(html).slideDown();
		$fullDetailsSection.after("<div class='clearfix'></div>");			
	};
		
	return {
		loadWorkDetails : function(){
			getDetailsFromWork(id);
		}
	}
};