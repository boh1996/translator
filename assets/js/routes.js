$(document).ready(function () {
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

	crossroads.addRoute("project/{project_id}/delete/language/{id}",function (project_id, language_id) {

	});

	crossroads.addRoute("project/{id}",function (id) {
		user(function () {
			$.ajax({
				url : root + "project/" + id + "?token="+token,
				success : function (data) {
					data = data.result;
					data.user = window.userData;
					var counter = 1;
					data["project_id"] = data.id;
					data["count"] = function () {
			            return function (text, render) {
			                return counter++;
			            }
			        };

					$("#project").html(Mustache.render($("#viewProjectTemplate").html(),data));
					showPage("project");
					$('[rel="tooltip"]').tooltip();
				},
				error : function () {
					error({
						"error" : language.errors_project_not_found
					});
				}
			});
		}, function () {
			error({
				"error" : language.errors_no_projects_not_found
			});
		});
    });

	crossroads.addRoute(/^project\/([0-9]+)\/([0-9]+)\/([0-9]+)$/, function (project_id, language_id, file_id) {
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
								user(function () {
									data = data.result;
									data.user = window.userData;
									data["modes"] = project_data.modes;
									data["project_id"] = project_data.id;
									data["language_id"] = language_id;
									data["project_name"] = project_data.name;
									data["language_name"] = language_data.name;
									data["file_id"] = file_id;

									for (var i = 0; i < data.keys.length; i++) {
										data.keys[i].modes = project_data.modes;
									};

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
								}, function () {
									error({
										"error" : language.errors_no_projects_not_found
									});
								});
							},
							error : function () {
								error({
									"error" : language.errors_project_not_found
								});
								return;
							}
						});
					},
					error : function () {
						error({
							"error" : language.errors_project_not_found
						});
						return;
					}
				});
			},
			error : function () {
				error({
					"error" : language.errors_language_not_found
				});
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
								error({
									"error" : language.errors_file_not_found
								});
								return;
							}
						});
					},
					error : function () {
						error({
							"error" : language.errors_project_not_found
						});
						return;
					}
				});
			},
			error : function () {
				error({
					"error" : language.errors_language_not_found
				});
				return;
			}
		});
	});

	crossroads.addRoute("project/{project_id}/{language_id}/{file_id}/edit", function (project_id, language_id, file_id) {

	});

	crossroads.addRoute("project/{project_id}/{language_id}/{{file_id}/delete", function (project_id, language_id, file_id) {

	});

    crossroads.addRoute("project/{project_id}/{language_id}/add/file", function (project_id, language_id) {
    	$.ajax({
			url : root + "language/" + language_id + "?token="+token,
			success : function (language_data) {
				language_data = language_data.result;
				$.ajax({
					url : root + "project/"+project_id + "?token="+token,
					success : function (data) {
						data = data.result;
						user(function () {
							data.user = window.userData;
							data.language_id = language_data.id;
							data.project_id = data.id;
							data.language_name = language_data.name;
							data.project_name = data.name;
							$("#project_language_add_file").html(Mustache.render($("#projectAddFileTemplate").html(),data));

							showPage("project_language_add_file");
						}, function () {
							error({
								"error" : language.errors_no_projects_not_found
							});
						});
					},
					error : function () {
						error({
							"error" : language.errors_project_not_found
						});
						return;
					}
				});
			},
			error : function () {
				error({
					"error" : language.errors_language_not_found
				});
				return;
			}
		});
    });

	crossroads.addRoute("project/{id}/add/language",function (id) {
		showPage("project_add_language");
	});

	crossroads.addRoute(/^project\/([0-9]+)\/([0-9]+)$/,function (project_id, language_id) {		
		$.ajax({
			url : root + "language/" + language_id + "?token="+token,
			success : function (language_data) {
				language_data = language_data.result;
				user(function () {
					$.ajax({
						url : root + "project/language/" + project_id+"/"+language_id + "?token="+token,
						success : function (data) {
							data = data.result;
							var counter = 1;
							data.user = window.userData;
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
							error({
								"error" : language.errors_project_language_not_found
							});
						}
					});
				}, function () {
					error({
						"error" : language.errors_no_projects_not_found
					});
				});
			},
			error : function () {
				error({
					"error" : language.errors_language_not_found
				});
				return;
			}
		});
	});

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
					error({
						"error" : language.error_occured_while_saving
					});
				}
			});
	    });
	}

	crossroads.bypassed.add(function(request) {
		error({
			"error" : language.errors_page_not_found
		});
	});
});

function homePage () {
	user(function (data) {
		$("#home").html(Mustache.render($("#projectsTemplate").html(),data));
		showPage("home");
	}, function () {
		error({
			"error" : language.errors_no_projects_not_found
		});
	});
}

/**
 * Request user data
 * 
 * @param  {function} successCallback A function to call when the data is ready
 * @param  {function} errorCallback   If the user request failed
 * @param {boolean} request If a request should be forced
 */
function user ( successCallback, errorCallback, request ) {
	if ( typeof window.userData != "undefined" && request != true ) {
		successCallback(window.userData);
		return;
	}

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

			window.userData = data;

			data.translate = function () {
				return oneMode("translate,create,edit,manage,delete", this);
			}

			data.delete = function () {
				return oneMode("delete,manage", this);
			}

			data.change = function () {
				return oneMode("edit,delete,manage,create,translate", this);
			}

			data.create = function () {
				return oneMode("create,manage", this);
			}

			data.edit = function () {
				return oneMode("edit,manage", this);
			}

			data.view = function () {
				return oneMode("edit,view,manage,delete,create,translate", this);
			}

			data.manage = function () {
				return oneMode("manage", this);
			}

			data.moderate = function () {
				return oneMode("manage,moderate", this);
			}

			if ( typeof successCallback == "function" ) {
				successCallback(data);
			}
		},
		error : function () {
			if ( typeof errorCallback == "function" ) {
				errorCallback();
			}
		}
	});
}

/**
 * Shows the error page
 * 
 * @param  {object} data The template data to use
 */
function error ( data ) {
	$("#error_page").html(Mustache.render($("#errorTemplate").html(),data));
	showPage("error_page");
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

	if ( typeof object == "undefined" ) return false;

	if ( typeof object.modes == "undefined" ) return false;

	for (var i = 0; i < modes.length; i++) {
		if ( object.modes.indexOf(modes[i]) !== -1 ) {
			return true;
		}
	};
	
	return false;
}