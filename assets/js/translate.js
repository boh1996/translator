$(".approve-decline button").live("click", function () {
	if ( $(this).attr("data-translation-id") == "" || $(this).attr("data-key-index") == "" ) {
		return;
	}
	$.post(root+"change/approval/"+$(this).attr("data-key-index")+"/"+$("#language_id").val()+"/"+$(this).attr("data-translation-id"),{
		"status" : ($(this).hasClass("approve")) ? true : false
	});
});

function checkTokens ( textarea ) {
	var value = $(textarea).val();

	$(textarea).parents(".language-key").find(".tokens-table").find("tr").each(function (index, element) {
		var token = $(element).find(".token").html();
		if ( value.indexOf(token.trim() ) == -1 ) {
			if ( $(element).hasClass("disabled-token") ) {
				$(element).removeClass("disabled-token");
			}
		} else {
			if ( ! $(element).hasClass("disabled-token") ) {
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
	insertText(" "+$(this).html(), $(textarea).next("input").next("iframe"));
	$(textarea).next("input").next("iframe").focus();
});

function insertText (text , frame) {
	frame = frame.get(0);
	var iframeWindow = frame.contentWindow;
	var iframeDocument = iframeWindow.document;
	var selection = null;
	var range = null;

	if (typeof iframeWindow.getSelection != "undefined") {
		selection = iframeWindow.getSelection();

		if (typeof selection.getRangeAt != "undefined")
		{
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

$.fn.extend({
    insertAtCaret: function(myValue) {
    	var element = $(this);
    	if ($(this).getCursorPosition() === false) return;
    	if ($(this).getCursorPosition() !== false) {
    		var startPos = $(this).getCursorPosition();
    		if (startPos === false) return; 
    		var endPos = this[0].selectionEnd;
            $(this).val($(this).val().substring(0, startPos)+myValue+$(this).val().substring(endPos,$(this).val().length));
            $(this).focus();
            this.selectionStart = startPos + myValue.length;
            this.selectionEnd = startPos + myValue.length;
        } else {
        	$(element).val($(element).val() + myValue);
            $(element).focus();
        }
    },
    getCursorPosition : function() {
        var el = $(this).get(0);
        var pos = false;
        if('selectionStart' in el) {
            pos = el.selectionStart;
        } else if('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
	}
});

function growTextarea (element) {
	if (countLines(element) == 1) {
		var contentHeight = countLines(element) * 18 + 10;
	} else {
		var contentHeight = countLines(element) * 18;
	}
	var element = $(element).get(0);
	$(element).css("overflow","hidden");
	var pad = parseInt($(element).css('padding-top'));
    if ($.browser.mozilla) 
    	 $(element).css("height",1);
    if (!$.browser.mozilla) 
        contentHeight -= pad * 2;
    if (contentHeight > parseInt($(element).css("height").replace("px",""))) 
    	if (contentHeight > 36)
    	    $(element).css("height",contentHeight);
    	else 
    		$(element).css("height",contentHeight+10);
    else
    	$(element).css("height",contentHeight+10);
}
function countLines(area) {
    var text = area.value.replace(/\s+$/g, "");
    var split = text.split(/\r\n|\r|\n/);
    return split.length;
}

$("textarea").each(function(){
    growTextarea(this);
});

$("textarea").keyup(function(){
    growTextarea(this);
});