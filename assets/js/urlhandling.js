var History = window.History;
$('[data-target]').live("click",function () {
	var url = "";

	if ( $(this).attr("data-toggle") == undefined ) {
	  	event.preventDefault();

	  	if (event.target.nodeName == 'A') {
		   	url = event.target.getAttribute('data-target');

		   	if ( url == "-back" ) {
		   		console.log("BACK");
		   		History.back();
		   		return;
		   	}

		    History.pushState(null,$("title").html(), root+url);
		}
	}
});