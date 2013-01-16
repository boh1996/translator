var History = window.History;
$('[data-target]').live("click",function () {
	  var url = "";

  	event.preventDefault();
  	if (event.target.nodeName == 'A') {
	   	url = event.target.getAttribute('data-target');
	    History.pushState(null,null, root+url);
	}
});