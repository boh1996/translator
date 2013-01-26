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
 * This function shows a page and change the navbar link properly
 * @param  {string} newPage The name of the page to change too
 */
function showPage (newPage) {
	$("#loading-background").hide();
	$("#loading").hide();
	var currentPage = $(".active_page");
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

/**
 * This function is a wrapper for the bootstrap alert system
 * @param  {object} data          Data for the Mustache template
 * @param  {string} template      An optional Mustcahe template for the content, if no data is supplied then this would be the text
 * @param  {string} templateId    The id without the # of the alert template to use
 * @param  {object} container     The container to insert the alert into
 * @param  {string} mode          The insert mode "prepend" or "append"
 * @param  {function} closeCallback A function to called when the alert is closed
 * @param  {integer} timeout       An optional time in ms the alert should be shown
 */
function alert (data, template, templateId, container, mode, closeCallback, timeout) {
	container = container || $("body");
	timeout = timeout || null;
	$("#"+templateId+"Clone").remove();
	var alert = $("#"+templateId).clone();
	var html = alert.html();
	if (data == null) {
		var content = template;
	} else {
		var content = Mustache.render(template,data);
	}
	alert.html(html.replace("{{message}}",content));
	alert.attr("id",templateId+"Clone");
	if (mode == "append") {
		$(container).append(alert);
	} else {
		$(container).prepend(alert);
	}

	if (timeout != null) {
		setTimeout(function () {
			$("#"+templateId+"Clone").alert("close");
		},timeout);
	}

	$(alert).bind('closed', function () {
		$(this).remove();
		if (typeof closeCallback == "function") {
			closeCallback();
		}
	});
}