(function ($) {
    //Autobuilder for a Bootstrap ScrollSpy
    //TO DO: refresh when element is dynamically created
    $(window).on('activate.bs.scrollspy', function (e) {
	history.replaceState({}, "", $("a[href^='#']", e.target).attr("href") || location.hash);
    });

    var defaults = {
	// These are the defaults.
	scrollSpyContainer: "#sendSidebar",
	scrollSpyCollector: "#taskSendPanel",
	scrollSpyFirstLevel: "h4",
	scrollSpySecondLevel: "label",
	circleIconClass: "glyphicon glyphicon-record",
	dummyLinksName: "scrollspylink",
	dummyLinkId: 1
    };

    var settings = {};

    $.scrollSpyBuild = function (options) {

	settings = $.extend({}, defaults, options);

	$.scrollSpyBuild.buildMenu(settings);
    };

    $.scrollSpyBuild.reBuildMenu = function () {
	$(settings.scrollSpyContainer).empty();
	$.scrollSpyBuild.buildMenu(settings);
    };

    $.scrollSpyBuild.reBuildMenuSection = function (sectionId) {
	$.scrollSpyBuild.buildMenu(settings);
    };

    $.scrollSpyBuild.buildMenu = function (settings) {
	var leftSlider = $(settings.scrollSpyContainer);
	var circleIcon = $("<span/>").attr("class", settings.circleIconClass).after("&nbsp;");


	$(settings.scrollSpyCollector).find(settings.scrollSpyFirstLevel).each(function (index, el) {
	    var el = $(el);
	    var linkId = '';

	    if (el.attr("id") === "undefined") {
		linkId = settings.dummyLinksName + settings.dummyLinkId;
		settings.dummyLinkId++;
	    }
	    else {
		linkId = el.attr("id");
	    }

	    var mainElement = $("<li/>").append(
		    $("<a/>").prop("href", "#" + linkId).attr("class", "scrollTo").html(el.html()).prepend(circleIcon.clone())
		    );

	    var subMenu = $("<ul/>").attr("class", "nav");
	    var subMenuDetails = $("<small/>").attr("style", "display: block; padding: 5px");

	    el.closest("div").next("div").find(settings.scrollSpySecondLevel).each(function (index, subel) {
		var subel = $(subel);

		if (!subel.hasClass("bootstrap-switch-label")) {
		    var subLinkId = '';

		    if (typeof subel.attr("id") === "undefined") {
			subLinkId = settings.dummyLinksName + settings.dummyLinkId;
			subel.attr("id", subLinkId);
			settings.dummyLinkId++;
		    }
		    else {
			subLinkId = subel.attr("id");
		    }

//		    subMenuDetails.append($.trim(subel.html())).append(", ");
		    var subElement = $("<li/>").prop("class", "doBorder").append(
			    $("<a/>").prop("href", "#" + subLinkId).attr("class", "scrollTo").html(subel.html())
			    );
		    subElement.appendTo(subMenu);
		}
	    });

	    subMenuDetails.appendTo(mainElement);
	    subMenu.appendTo(mainElement);

	    mainElement.appendTo(leftSlider);
	});


    };
}(jQuery));