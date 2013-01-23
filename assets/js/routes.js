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
					data : JSON.stringify(data),
					contentType: 'application/json',
          			dataType: 'json',
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
		    	$("#edit_project_form").submit(function (event) {
			    	event.preventDefault();
					$("#edit_project_form .alert").remove();	

					var data = {
						"name" : $("#edit_project_name").val()
					};

					if ($("#edit_project_location").val() != "") {
						data.location = $("#edit_project_location").val();
					}

					$.ajax({
						url : root + "project/"+id,
						type : "PUT",
						data : JSON.stringify(data),
						contentType: 'application/json',
           				dataType: 'json',
						success : function () {
							alert(null,translations.project_saved,"alertsSuccessTemplate", $("#edit_project_form"), "prepend" , function () {
								History.pushState(null,null,root); 
							}, 2000); 
						},
						error : function (xhr) {
							if (xhr.status == 403) {
								alert(null,translations.error_no_permission,"alertsErrorTemplate", $("#edit_project_form"), "prepend", function () {
									History.pushState(null,null,root); 
								}, 2000);
							} else if (xhr.status == 404) {
								alert(null,translations.error_no_project_found,"alertsErrorTemplate", $("#edit_project_form"), "prepend", function () {
									History.pushState(null,null,root); 
								}, 2000);
							} else {
								alert(null,translations.error_sorry_error_occured,"alertsErrorTemplate", $("#edit_project_form"), "prepend", null, 2000);
							}
						}
					});
				});
		    })
		    .error(function ( ) {
		    	History.pushState(null,null,root); 
		    });
	    });
	}

	if ($("#project").length > 0) {
		crossroads.addRoute("project/{{project_id}}/delete/language/{{id}}",function (project_id, language_id) {

		});

		crossroads.addRoute("project/{id}",function (id) {
			$.ajax({
				url : root + "project/" + id,
				success : function (data) {
					var counter = 1;
					data["project_id"] = data.id;
					data["count"] = function () {
			            return function (text, render) {
			                return counter++;
			            }
			        };
					$("#project").html(Mustache.render($("#viewProjectTemplate").html(),data));
					showPage("project");
				},
				error : function () {

				}
			});
	    });

		crossroads.addRoute("project/{{project_id}}/{{language_id}}/{{file_id}}", function (project_id, language_id, file_id) {

		});

		crossroads.addRoute("project/{{project_id}}/{{language_id}}/{{file_id}}/edit", function (project_id, language_id, file_id) {

		});

		crossroads.addRoute("project/{{project_id}}/{{language_id}}/{{file_id}}/delete", function (project_id, language_id, file_id) {

		});

	    crossroads.addRoute("project/{{project_id}}/{{language_id}}/add/file", function (project_id, language_id) {
	    	showPage("project_language_add_file");
	    	/**
	    	 * data {
	    	 * 	project_id
	    	 * 	language_id
	    	 * 	language_name
	    	 * 	project_name
	    	 * }
	    	 */
	    });

		crossroads.addRoute("project/{id}/add/language",function (id) {
			showPage("project_add_language");
		});

		crossroads.addRoute("project/{id}/{language_id}",function (project_id, language_id) {		
			$.ajax({
				url : root + "language/" + language_id,
				success : function (language_data) {
					$.ajax({
						url : root + "project/" + project_id,
						success : function (data) {
							var counter = 1;
							data["project_id"] = data.id;
							data["language_id"] = language_id;
							data["language_name"] = language_data.name;
							data["count"] = function () {
					            return function (text, render) {
					                return counter++;
					            }
					        };
							$("#project_language").html(Mustache.render($("#projectLanguageFilesTemplate").html(),data));
							showPage("project_language");
						},
						error : function () {
							
						}
					});
				},
				error : function () {
					//Show language not found
					return;
				}
			});
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