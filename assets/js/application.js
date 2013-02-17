History.Adapter.bind(window,'statechange',function(){
	crossroads.parse(getPage());
	crossroads.resetState();
});

$(document).ready(function() {
	crossroads.parse(getPage());
	crossroads.resetState();
});

var result;

$(document).on("click", "#add_language_search_token", function () {
	var modal = $($("#searchTokenModalTemplate").html());
	modal.attr("id","search_token_modal");
	$("body").append(modal);
	$("#search_token_modal").modal();

	$("#search_token").typeaheadmap({
		"notfound": new Array({'token' :translations.token_not_found, 'id': "not_found"}),
		source : function (query, process) {
			$.get(root + "token/get/"+$("#language_key_project_id").val()+".json").success(function (data) {
				result = data;

				var lines = new Array();

				for (var key in data) {
					if ( $("#create_language_key_tokens").find('tr[data-index="'+data[key].id+'"]').length == 0 ) {
						lines.push(data[key]);
					}
				};

				process(lines);
				
			});
		},

		displayer: function(that, item, highlighted) {
			var object = findByProperty(result, "id", item.id);
			if (typeof object !== "undefined") {
		    	return highlighted + " - " + "<i>" + object.description + "</i>";
		    } else {
		    	return "<strong>"+that["notfound"][0][that.key]+"</strong>";
		    }
		},
		listener : function ( token, id ) {
			if ( id == "not_found" ) {
				$("#search_token_modal").modal("hide");
				return;
			}
			var object = findByProperty(result, "id", id);
			alert(null,translations.token_added,"alertsSuccessTemplate", $("#search_token_body"), "prepend", function () {
				$("#search_token_modal").modal("hide");
			}, 700);
			$("#create_language_key_tokens").append(Mustache.render($("#tokenTemplate").html(),object));
			$("[rel='tooltip']").tooltip();
		},
		items : 5,
		key : "token",
		value : "id"
	});
});

$(document).on("hide", "#search_token_modal", function () {
	$("#search_token_modal").remove();
});

$(document).on("click", "#add_language_keycreate_token_button", function () {
	var modal = $($("#createTokenModalTemplate").html());
	modal.attr("id","create_token_modal");
	$("body").append(modal);
	$("#create_token_modal").modal();

	$("#token_name").typeaheadmap({
		"notfound": new Array({'token' :translations.token_not_found, 'id': "not_found"}),
		source : function (query, process) {
			$.get(root + "token/get/"+$("#language_key_project_id").val()+".json").success(function (data) {
				result = data;

				var lines = new Array();

				for (var key in data) {
					if ( $("#create_language_key_tokens").find('tr[data-index="'+data[key].id+'"]').length == 0 ) {
						lines.push(data[key]);
					}
				};

				process(lines);
				
			});
		},
		displayer: function(that, item, highlighted) {
			var object = findByProperty(result, "id", item.id);
			if (typeof object !== "undefined") {
		    	return highlighted + " - " + "<i>" + object.description + "</i>";
		    } else {
		    	return "<strong>"+that["notfound"][0][that.key]+"</strong>";
		    }       
		},
		listener : function ( token, id ) {
			if ( id == "not_found" ) {
				$("#create_token_modal").modal("hide");
				return;
			}
			var object = findByProperty(result, "id", id);
			alert(null,translations.token_added,"alertsSuccessTemplate", $("#create_token_form"), "prepend", function () {
				$("#create_token_modal").modal("hide");
			}, 700);
			$("#create_language_key_tokens").append(Mustache.render($("#tokenTemplate").html(),object));
			$("[rel='tooltip']").tooltip();
		},
		items : 5,
		key : "token",
		value : "id"
	});
});

function findByProperty ( array, property, value) {
	for (var i = 0; i < array.length; i++) {
		if ( array[i][property] == value ) {
			return array[i];
		}
	}
}

$(document).on("click", "#create_token_save_button", function () {
	if ( $("#create_token_form").find("#token_name").val() !== "" && $("#create_token_form").find("#token_name").val() !== undefined ) {
		var data = {
			"token" : $("#create_token_form").find("#token_name").val(),
			"project" : $("#language_key_project_id").val()
		};

		if ( $("#create_token_form").find("#token_description").val() !== undefined ) {
			data.description = $("#create_token_form").find("#token_description").val();
		}
		$.ajax({
			url : root + "token",
			type : "POST",
			data : JSON.stringify(data),
			contentType: 'application/json',
          	dataType: 'json',
		}).success(function (data) {
			alert(null,translations.token_created,"alertsSuccessTemplate", $("#create_token_form"), "prepend", function () {
				$("#create_token_modal").modal("hide");
			}, 2000);
			$("#create_language_key_tokens").append(Mustache.render($("#tokenTemplate").html(),data));
			$("[rel='tooltip']").tooltip();
		})
		.error(function () {
			alert(null,translations.error_sorry_error_occured,"alertsErrorTemplate", $("#create_token_form"), "prepend", function () {
				$("#create_token_modal").modal("hide");
			}, 2000);
		});
	} else {
		alert(null,translations.create_token_missing_token_name,"alertsErrorTemplate", $("#create_token_form"), "prepend", null, 2000);
	}
});

$(document).on("click", "#edit_token_save_button", function () {
	var data = {
		"project" : $("#language_key_project_id").val()
	};
	var updated = false;

	if ( $("#edit_token_name").val() != $("#create_language_key_container").find(".tokens").find('tr[data-index="'+$(this).attr("data-index")+'"]').find(".token-text").find("a").attr("data-token") ) {
		updated = true;
		data.token = $("#edit_token_name").val();
	}

	if ( $("#edit_token_description").val() != $("#create_language_key_container").find(".tokens").find('tr[data-index="'+$(this).attr("data-index")+'"]').find(".token-description").find("i").attr("data-description") ) {
		updated = true;
		data.description = $("#edit_token_description").val();
	}

	if ( updated && $("#edit_token_name").val() != "" ) {
		$.ajax({
			url : root + "token/"+$(this).attr("data-index"),
			type : "PUT",
			data : JSON.stringify(data),
			contentType: 'application/json',
	      	dataType: 'json',
		}).success(function (data) {
			alert(null,translations.token_updated,"alertsSuccessTemplate", $("#edit_token_modal").find(".modal-body"), "prepend", function () {
				$("#edit_token_modal").modal("hide");
			}, 1000);
			$("#create_language_key_container").find(".tokens").find('tr[data-index="'+data.id+'"]').replaceWith(Mustache.render($("#tokenTemplate").html(),data));
			$("[rel='tooltip']").tooltip();
		})
		.error(function () {
			alert(null,translations.error_sorry_error_occured,"alertsErrorTemplate", $("#edit_token_modal").find(".modal-body"), "prepend", function () {
				$("#edit_token_modal").modal("hide");
			}, 2000);
		});
	}
});

$(document).on("click", "#create_language_key_tokens a.edit-token", function () {
	var html = $("#editTokenModalTemplate").html();

	var description = $(this).parent("td").parent("tr").find(".token-description").find("i").html();

	var data = {
		"token" : $(this).parent("td").parent("tr").find(".token-text").find("a").attr("data-token"),
		"id" : $(this).parent("td").parent("tr").find(".token-text").find("a").attr("data-index"),
		"description" : (description != "") ? description : null, 
	};

	var modal = $(Mustache.render(html,data));
	modal.attr("id","edit_token_modal");
	$("body").append(modal);
	$("#edit_token_modal").modal();
});

$(document).on("hide", "#edit_token_modal", function () {
	$("#edit_token_modal").remove();
});

$(document).on("click", "#create_language_key_tokens a.delete-token", function () {
	$(this).parent("td").parent("tr").remove();
});


$(document).on("hide", "#create_token_modal", function () {
	$("#create_token_modal").remove();
});

$(document).on("submit","#create_language_key_form",function (event) {
	event.preventDefault();
	if ( $("#key_name").val() !== "" && $("#key_name").val() !== undefined ) {
		var data = {
			"key" : $("#key_name").val()
		};
		if ( $("#key_description").val() !== "" && $("#key_description").val() !== undefined ) {
			data.description = $("#key_description").val();
		}
		data.approve_first = ($("#approve_first_group").find(".btn-success").hasClass("active")) ? "true" : "false" ;

		if ( $("#create_language_key_tokens").find("tr").length > 0 ) {
			for (var i = 0; i < $("#create_language_key_tokens").find("tr").length; i++) {
				if ( typeof data.tokens !== "undefined" ) {
					data.tokens.push($("#create_language_key_tokens").find("tr:eq("+i+")").attr("data-index"));
				} else {
					data.tokens = new Array();
					data.tokens.push($("#create_language_key_tokens").find("tr:eq("+i+")").attr("data-index"));
				}
			};
		}	

		$.ajax({
			type : "POST",
			url : root+"file/"+$("#language_key_file_id").val()+"/add/key",
			data : JSON.stringify(data),
			contentType: 'application/json',
          	dataType: 'json',
		})
		.success(function () {
			alert(null,translations.language_key_created,"alertsSuccessTemplate", $("#create_language_key_form"), "prepend", function () {
				History.pushState(null,$("title").html(), root+"project/"+$("#language_key_project_id").val()+"/"+$("#language_key_language_id").val()+"/"+$("#language_key_file_id").val());
			}, 2000);
		})
		.error(function () {
			//An error occurred
		});
	} else {
		// Show error key missing
	}
});