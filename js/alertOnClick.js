/* 
 * alertsOnClick 
 * Shows a small alert div, with options "Yes" or "No" to confirm the previous click. Works with inputs also.
 * Format for the link: <a href='/some/link/to/be/clicked' class='classToBeAttachedToEvent' title='Text to be displayed when asking'>  
 * Format for the input=submit: <input type='submit' title='Text to be displayed when asking' class='classToBeAttachedToEvent' data-submit='1' value='Ok' />  
 * 
 */

(function ($) {

    var defaults = {
	// These are the defaults.

	//Default text for the button, which confirms the action
	yesButtonText: "Yes",
	//Default text for the button, which denies the action
	noButtonText: "No",
	//Id for the alert boxes
	dummyLinksName: "newAlertDialog",
	//Starting number of "dummyLinksName"
	dummyLinkId: 1
    };

    var settings = {};

    $.showAlertOnClick = function (options) {
	settings = $.extend({}, defaults, options);
    };

    $.showAlertOnClick.showWarning = function (link, clicked) {
	$(clicked).toggleClass("hidden");
	var linkId = settings.dummyLinksName + settings.dummyLinkId;
	settings.dummyLinkId++;

	$("<div/>").prop({
	    "id": linkId
	}).appendTo($(clicked).parent());

	var newDiv = $("#"+linkId);

	newDiv.addClass("alert").addClass("alert-danger text-center").text($(clicked).attr("title"));

	$("<div/>").addClass("clerafix").appendTo(newDiv);

	if ($(clicked).data("submit") == "1")
	    $("<input/>").attr("type", "submit").attr("value", settings.yesButtonText).addClass("btn btn-xs btn-success pull-left").text(settings.yesButtonText).appendTo(newDiv);
	else
	    $("<a/>").attr("href", link).addClass("btn btn-xs btn-success pull-left").text(settings.yesButtonText).appendTo(newDiv);

	$("<a/>").attr("href", "#").addClass("btn btn-xs btn-danger pull-right").text(settings.noButtonText).on("click", function () {
	    $(clicked).toggleClass("hidden");
	    $(newDiv).remove();
	}).appendTo(newDiv);

	$("<div/>").addClass("clearfix").appendTo(newDiv);
	$("<div/>").addClass("clearfix").prependTo(newDiv);
    }
}(jQuery));

