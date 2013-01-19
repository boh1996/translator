History.Adapter.bind(window,'statechange',function(){
	crossroads.parse(getPage());
	crossroads.resetState();
});

$(document).ready(function() {
	crossroads.parse(getPage());
	crossroads.resetState();
});