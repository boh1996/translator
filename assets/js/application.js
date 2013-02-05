History.Adapter.bind(window,'statechange',function(){
	crossroads.parse(getPage());
	crossroads.resetState();
});

$(document).ready(function() {
	crossroads.parse(getPage());
	crossroads.resetState();
});

$(document).on("click", "#add_language_keycreate_token_button", function () {
	var modal = $($("#createTokenModalTemplate").html());
	modal.attr("id","create_token_modal");
	$("body").append(modal);
	$("#create_token_modal").modal();
});

$(document).on("click", "#create_token_save_button", function () {
	if ( $("#create_token_form").find("#token_name").val() !== "" && $("#create_token_form").find("#token_name").val() !== undefined ) {
		var data = {
			"token" : $("#create_token_form").find("#token_name").val()
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

$(document).on("click", "#create_language_key_tokens a.edit-token", function () {
	
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
			//Success key created
		})
		.error(function () {
			//An error occurred
		});
	} else {
		// Show error key missing
	}
});