$(".approve-decline button").live("click", function () {
	if ( $(this).attr("data-translation-id") == "" || $(this).attr("data-key-index") == "" || $(this).attr("data-translation-id") == false || $(this).attr("data-translation-id") == null ||$(this).attr("data-translation-id") == undefined ) {
		return;
	}
	$.post(root+"change/approval/"+$(this).attr("data-key-index")+"/"+$("#language_id").val()+"/"+$(this).attr("data-translation-id"),{
		"status" : ($(this).hasClass("approve")) ? true : false
	});
});

$(".save-translations").live("click", function () {
	var data = {
		"project_id" : $("#project_id").val(),
		"language_id" : $("#language_id").val(),
		"translations" : []
	};
	for (var i = 0; i < $(".language-key").length; i++) {
		var key = $(".language-key:eq("+i+")");
		var translation = translationData(key);
		if ( translation !== false ) {
			data["translations"].push(translation);
		} else {
			alert(null, translations.error_multiple_translation_fields_empty, "alertsErrorTemplate", $(".translations") , "prepend", null, 2000);
		}
	};
	if ( data["translations"].length > 0 ) {
		sendTranslations(data);
	}
});

function sendTranslations ( data ) {
	$.post(root+"translations", data).success(function () {
		alert(null, translations.data_saved, "alertsSuccessTemplate", $(".translations") , "prepend", null, 2000);	
	}).error(function () {
		alert(null, translations.error_sorry_error_occured, "alertsErrorTemplate", $(".translations") , "prepend", null, 2000);	
	});
}

/**
 * This function assemblies an object for the selected language key
 * @param  {Object} languageKey The language key DOM Element to retrieve data for
 * @return {Object}
 */
function translationData ( languageKey ) {
	if ( getTranslation(languageKey) != "" && allTokensUsed(languageKey) ) {
		return ({
			"translation" : getTranslation(languageKey),
			"approval" : getApprovalStatus(languageKey),
			"language_key_id" : $(languageKey).attr("data-index")
		});
	} else {
		return false;
	}
}

/**
 * This function gets the approval status for a translation key
 * @param  {Object} languageKey The language key DOM Element to check for
 * @return {boolean}
 */
function getApprovalStatus ( languageKey ) {
	return ($(languageKey).find(".approve-decline").find(".active").hasClass("approve")) ? true : false;
}

$(".save-translation").live("click", function ( index, element) {
	var key = $(this).parent("div").parent(".language-key");
	var data = {
		"project_id" : $("#project_id").val(),
		"language_id" : $("#language_id").val(),
		"translations" : []
	};
	var translation = translationData(key);
	if ( translation !== false ) {
		data["translations"].push(translation);
		sendTranslations(data);
	} else {
		alert(null, translations.error_no_translation, "alertsErrorTemplate", $(".translations") , "prepend", null, 2000);
	}
});

/**
 * This function returns the translated text for langaugeKey
 * @param  {Object} languageKey The language key DOM element to take the translation from
 * @return {string}
 */
function getTranslation ( languageKey ) {
	return $(languageKey).find("textarea").val();
}

/**
 * Tihs function checks if all the requested tokens are used
 * @param  {Object} languageKey The language key DOM Element to check in
 * @return {boolean}
 */
function allTokensUsed ( languageKey ) {
	return $(languageKey).find(".tokens-table").find(".active-token").length == 0;
}

/**
 * This function checks if any of the tokens is present in the translation
 * @param  {Object} languageKey The language key to check in
 */
function checkTokenState ( languageKey ) {
	var value = $(languageKey).find("textarea").val();
	$(languageKey).find(".token").each(function (index, element) {
		if ( value.indexOf($(element).attr("data-token")) != -1 ) {
			$(element).parent("td").parent("tr").removeClass("active-token");
			$(element).parent("td").parent("tr").addClass("disabled-token");
		} else {
			$(element).parent("td").parent("tr").addClass("active-token");
			$(element).parent("td").parent("tr").removeClass("disabled-token");
		}
	});
}

/**
 * This function checks the textarea to see if the correct disabled tokens
 * are stil in the translations
 * @param  {object} textarea The "textarea" to check in
 */
function checkTokens ( textarea ) {
	var value = $(textarea).val();

	$(textarea).parents(".language-key").find(".tokens-table").find("tr").each(function (index, element) {
		var token = $(element).find(".token").attr("data-token");
		if ( value.indexOf(token.trim() ) == -1 ) {
			if ( $(element).hasClass("disabled-token") ) {
				$(element).addClass("active-token");
				$(element).removeClass("disabled-token");
			}
		} else {
			if ( ! $(element).hasClass("disabled-token") ) {
				$(element).removeClass("active-token");
				$(element).addClass("disabled-token");
			}
		}
	});
}

$(".token").live("click",function() {
	var textarea = $(this).parent("td").parent("tr").parent("tbody").parent("table").parent("div").prev(".translate-area-container").find("textarea").get(0);
	if (typeof textarea == "undefined" || textarea.length == 0) {
		return;
	}
	$(this).parent("td").parent("tr").addClass("disabled-token");
	$(this).parent("td").parent("tr").removeClass("active-token");
	insertText(" "+$(this).html(), $(textarea).next("input").next("iframe"));
	$(textarea).next("input").next("iframe").focus();
});

/**
 * This function inserts "text" at the position of a cursor
 * @param  {String} text  The text to insert
 * @param  {Object} frame The iframe to insert it into
 */
function insertText (text , frame) {
	frame = frame.get(0);
	var iframeWindow = frame.contentWindow;
	var iframeDocument = iframeWindow.document;
	var selection = null;
	var range = null;

	if (typeof iframeWindow.getSelection != "undefined") {
		selection = iframeWindow.getSelection();

		if (selection.type != "None") {
			if (typeof selection.getRangeAt != "undefined") {	
				range = selection.getRangeAt(0);
			} else if (typeof selection.baseNode != "undefined"){
				range = iframeDocument.createRange();
				range.setStart(selection.baseNode, selection.baseOffset);
				range.setEnd(selection.extentNode, selection.extentOffset);

				if (range.collapsed) {
					range.setStart(selection.extentNode, selection.extentOffset);
				    range.setEnd(selection.baseNode, selection.baseOffset);
				}
			}

			var rangeCopy = range.cloneRange();
			var insertText = iframeDocument.createTextNode(text);

			rangeCopy.collapse(true);
			range.deleteContents();
			rangeCopy.insertNode(insertText);

			selection.collapse(insertText, text.length);
		} else {
			var insertText = iframeDocument.createTextNode(text);
			$('body', frame.contentDocument).append(text);
		}
	} else if (typeof iframeDocument.selection != "undefined") {
		selection = iframeDocument.selection;
		range = selection.createRange();
		range.pasteHTML(text);
	} else {
		return false;
	}

	iframeWindow.focus();

	return true;
}

function countLines(area) {
    var text = area.value.replace(/\s+$/g, "");
    var split = text.split(/\r\n|\r|\n/);
    return split.length;
}