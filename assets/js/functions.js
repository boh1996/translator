/**
 * This function replaces the template variables with the correct data
 * @param  {string} string The template
 * @param  {object} data   The template variables and values
 * @param {boolean} propagate If the childs should be templated too
 * @return {string}
 */
function template (string,data, propagate) {
	for(var variable in data)
	{	
		var replacement = data[variable];
		if (variable.indexOf("{") !== -1 && variable.indexOf("}") !== -1 && propagate !== false) {
			variable = template(variable,data,false);
		}
		if (replacement.indexOf("{") !== -1 && replacement.indexOf("}") !== -1 && propagate !== false) {
			replacement = template(replacement,data,false);
		}
		
	    string = string.replace("{"+variable+"}",replacement);
	}
	return string;
}

/**
 * This function sets the document title
 * @param {object} data Template data
 */
function setTitle (data) {
	var variables = {
		"brand" : front_translations.brand_title,
	}
	document.title = template(front_translations.page_template,application.merge_options(data,variables),true);
}

/**
 * This function shows a page and change the navbar link properly
 * @param  {string} newPage The name of the page to change too
 */
function showPage (newPage) {
	$("#loading-background").hide();
	$("#loading").hide();
	var currentPage = $(".active_page");
	if (objx.get(generators,currentPage.attr("id")) != null) {
		var id = objx.get(generators,currentPage.attr("id"));
		objx.get(application,id).hide();
	}
	$(".active").removeClass("active");
   	if ($("#"+newPage).length > 0) {
   		$(".active_page").addClass("disabled_page").removeClass("active_page");
   		$("#"+newPage).removeClass("disabled_page").addClass("active_page");
   		if ($('a[data-target="'+newPage+'"]').length > 0 && !$('a[data-target="'+newPage+'"]').parent("li").hasClass("active")) {
   			if ($('a[data-target="'+newPage+'"]').attr("data-no-active") != "true") {
   				$('a[data-target="'+newPage+'"]').parent("li").addClass("active");
   			}
   		}
   	}
}

/**
 * This function returns the current "page",
 * ready to use with the URL Routing system
 * @return {string}
 */
function getPage () {
	return History.getState().url.replace(root,"");
}