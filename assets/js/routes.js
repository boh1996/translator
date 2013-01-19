$(document).ready(function () {

	if ($("#project_create").length > 0) {
		crossroads.addRoute("project/create",function () {
			$("#create_project_form").find("input[type=text], textarea").val("");
			showPage("project_create");
			$("#project_name").focus();
			$("#create_project_form").submit(function (event) {
				$("#create_project_form .alert").remove();
				event.preventDefault();

				var data = {
					"name" : $("#project_name").val()
				};

				if ($("#project_location").val().length > 0) {
					data.location = $("#project_location").val();
				}

				$.ajax({
					url : root + "project",
					type : "POST",
					data : data,
					success : function () {
						alert(null,translations.project_saved,"alertsSuccessTemplate", $("#create_project_form"), "prepend" , function () {
							History.pushState(null,null,root); 
						}, 2000); 
					},
					error : function (xhr) {
						if (xhr.status == 403) {
							alert(null,translations.error_no_permission,"alertsErrorTemplate", $("#create_project_form"), "prepend", function () {
								History.pushState(null,null,root); 
							}, 2000);
						} else if (xhr.status == 409) {
							alert(null,translations.error_project_found,"alertsErrorTemplate", $("#create_project_form"), "prepend", function () {
								History.pushState(null,null,root); 
							}, 2000);
						} else {
							alert(null,translations.error_sorry_error_occured,"alertsErrorTemplate", $("#create_project_form"), "prepend", null, 2000);
						}
					}
				});
			});
	    });
	}

	if ($("#project_edit").length > 0) {
		crossroads.addRoute("project/edit/{id}",function (id) {

			var jqxhr = $.get(root + "project/"+id)
			 .success(function(data) { 
		    	$("#project_edit").html("");
		    	$("#project_edit").html(Mustache.render($("#editProjectViewTemplate").html(), data));
		    	showPage("project_edit");
		    })
		    .error(function ( ) {
		    	History.pushState(null,null,root); 
		    });
	    });
	}

	if ($("#project").length > 0) {
		crossroads.addRoute("project/{id}",function (id) {
			$("#loading-background").show();
			$("#loading").show();
			showPage("project");
	    });
	}

	crossroads.addRoute("",function () {
		showPage("home");
	});

	crossroads.addRoute("home",function () {
		showPage("home");
	});

	if ( $("#deleteProjectConfirmModalTemplate").length > 0) {
		crossroads.addRoute("project/delete/{id}",function (id) {
			$.ajax({
				url : root + "project/" + id,
				success : function (data) {
					if ($("#deleteProjectConfirmModal").length == 0) {
						$("body").append('<div id="deleteProjectConfirmModal"></div>');
					}
					$("#deleteProjectConfirmModal").html(Mustache.render($("#deleteProjectConfirmModalTemplate").html(), data));
					$("#deleteProjectConfirmModal div:first").modal("show")
					$("#deleteProjectConfirmModal div:first").on("hide",function () {
						History.pushState(null,null,root); 
					});

					$("#deleteProjectConfirmModal").find(".btn-primary").live("click", function () {
						$.ajax({
							url : root + "project/"+id,
							type : "DELETE",
							success : function () {
								$('[data-index="'+id+'"]').parent("tr").remove();
							}
						});
					});
				},
				error : function () {

				}
			});
	    });
	}

	crossroads.bypassed.add(function(request){
		/*$("#loading-background").show();
		$("#loading").show();
	   	showPage("errorPage");
	   	setTitle({
			"page" : front_translations.page_not_found
		});*/
	});
});