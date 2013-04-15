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
					url : root + "project" + "?token="+token,
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

			var jqxhr = $.get(root + "project/"+id + "?token="+token)
			 .success(function(data) { 
			 	data = data.result;
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
						url : root + "project/"+id + "?token="+token,
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
		crossroads.addRoute("project/{project_id}/delete/language/{id}",function (project_id, language_id) {

		});

		crossroads.addRoute("project/{id}",function (id) {
			$.ajax({
				url : root + "project/" + id + "?token="+token,
				success : function (data) {
					data = data.result;
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
					//Project not found
				}
			});
	    });

		crossroads.addRoute("project/{project_id}/{language_id}/{file_id}", function (project_id, language_id, file_id) {
			$.ajax({
				url : root + "language/" + language_id + "?token="+token,
				success : function (language_data) {
					language_data = language_data.result;
					$.ajax({
						url : root + "project/"+project_id + "?token="+token,
						success : function (project_data) {
							project_data = project_data.result;
							$.ajax({
								url : root + "project/"+project_id+"/language/"+language_id+"/file/"+file_id + "?token="+token,
								success : function (data) {
									data = data.result;
									data["project_id"] = project_data.id;
									data["language_id"] = language_id;
									data["project_name"] = project_data.name;
									data["language_name"] = language_data.name;
									data["file_id"] = file_id;
									$("#language_file").html(Mustache.render($("#languageFileTemplate").html(),data));
									showPage("language_file");
									$(".language-key").each(function (index, element) {
										checkTokenState(element);
									});
									$('[data-toggle="button"]').button('toggle');
									$('.translate-field').wysihtml5({
										"stylesheets": [],
										"font-styles": false,
										"emphasis": false,
										"lists": false,
										"html": false,
										"link": false,
										"image": false,
										"color": false,
										"events" : {
											"change" : function () {
												checkTokens(this.textareaElement);
											}
										}
									});
									$(".translate-field").each(function(index, element) {
										var editor = $(element).data("wysihtml5").editor;
									    editor.observe("load", function() {
								            editor.composer.element.addEventListener("keyup", function() {
								            	checkTokens(element);
								            });
							            });
									});
								},
								error : function () {
									// Show project not found
									return;
								}
							});
						},
						error : function () {
							// Show project not found
							return;
						}
					});
				},
				error : function () {
					//Show language not error_no_project_found
					return;
				}
			});
		});

		crossroads.addRoute("language/key/{language_key_id}/edit",function ( language_key_id ) {
			var data = {};
			$("#language_key_edit").html(Mustache.render($("#editLanguageKeyTemplate").html(),data));
			showPage("language_key_edit");
		});

		crossroads.addRoute("language/key/{language_key_id}/delete",function ( language_key_id ) {
			//showPage("language_key_edit");
		});

		crossroads.addRoute("project/{project_id}/{language_id}/{file_id}/add/key",function ( project_id, language_id, file_id ) {
			$.ajax({
				url : root + "language/" + language_id + "?token="+token,
				success : function (language_data) {
					language_data = language_data.result;
					$.ajax({
						url : root + "project/"+project_id + "?token="+token,
						success : function (project_data) {
							project_data = project_data.result;
							$.ajax({
								url: root + "file/"+file_id + "?token="+token,
								success : function ( file_data ) {
									file_data = file_data.result;
									var data = {	
										"file" : file_data,
										"language" : language_data,
										"project" : project_data
									};
									$("#add_language_key").html(Mustache.render($("#addLanguageKeyTemplate").html(),data));
									showPage("add_language_key");
									$('[data-toggle="button"]').button('toggle');
								},
								error : function () {
									// Show error file not found
									return;
								}
							});
						},
						error : function () {
							// Show project not found
							return;
						}
					});
				},
				error : function () {
					//Show language not found
					return;
				}
			});
		});

		crossroads.addRoute("project/{project_id}/{language_id}/{file_id}/edit", function (project_id, language_id, file_id) {

		});

		crossroads.addRoute("project/{project_id}/{language_id}/{{file_id}/delete", function (project_id, language_id, file_id) {

		});

	    crossroads.addRoute("project/{project_id}/add/file", function (project_id, language_id) {
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

		crossroads.addRoute(/^project\/([0-9]+)\/([0-9]+)$/,function (project_id, language_id) {		
			$.ajax({
				url : root + "language/" + language_id + "?token="+token,
				success : function (language_data) {
					language_data = language_data.result;
					$.ajax({
						url : root + "project/language/" + project_id+"/"+language_id + "?token="+token,
						success : function (data) {
							data = data.result;
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
							$('[rel="tooltip"]').tooltip();
						},
						error : function () {
							//Project Language not found
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
		homePage();
	});

	crossroads.addRoute("home",function () {
		homePage();		
	});

	if ( $("#deleteProjectConfirmModalTemplate").length > 0) {
		crossroads.addRoute(/^project\/delete\/([0-9]+)/,function (id) {
			$.ajax({
				url : root + "project/" + id + "?token="+token,
				success : function (data) {
					data = data.result;
					if ($("#deleteProjectConfirmModal").length == 0) {
						$("body").append('<div id="deleteProjectConfirmModal"></div>');
					}
					$("#deleteProjectConfirmModal").html(Mustache.render($("#deleteProjectConfirmModalTemplate").html(), data));
					$("#deleteProjectConfirmModal div:first").modal("show")
					$("#deleteProjectConfirmModal div:first").on("hide",function () {
						History.pushState(null,null,root); 
					});

					$("#deleteProjectConfirmModal").on("click",".btn-primary", function () {
						$.ajax({
							url : root + "project/"+id + "?token="+token,
							type : "DELETE",
							success : function () {
								$("#home").find('[data-index="'+id+'"]').parent("td").parent("tr").remove();
							}
						});
					});
				},
				error : function () {
					//An error occurred while saving
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

function homePage () {
	$.ajax({
		url : root + "me?token=" + token,
		success : function ( data ) {
			data = data.result;
			var number = 0;

			data.number = function () {
				return function () {
					number++;
					return number;
				}
			}

			data.create = function () {
				return oneMode("create", this);
			}

			data.delete = function () {
				return oneMode("delete", this);
			}

			data.edit = function () {
				return oneMode("edit", this);
			}

			$("#home").html(Mustache.render($("#projectsTemplate").html(),data));

			showPage("home");
		},
		error : function () {
			// Sorry No projects found
		}
	});
}

/**
 * Checks user project access mode,
 * if one of the selected modes are found,
 * true is returned
 * 
 * @param  {string} modes  A commaseperated string
 * @param  {object} object The Project object
 * @return {boolean}
 */
function oneMode ( modes, object ) {
	var modes = modes.split(",");

	if ( typeof object.modes == "undefined" ) return false;

	for (var i = 0; i < modes.length; i++) {
		if ( object.modes.indexOf(modes[i]) !== -1 ) {
			return true;
		}
	};
	
	return false;
}