
jQuery(document).ready(function($) { 
	$(".hasPopover").popover({"html": true,"trigger": "hover focus","container": "body"});
	if (window.toggleSidebar)
		{
			toggleSidebar(true);
		}
		else
		{
			$("#j-toggle-sidebar-header").css("display", "none");
			$("#j-toggle-button-wrapper").css("display", "none");
		} 
});

if (typeof Virtuemart === "undefined")
    Virtuemart = {};

Virtuemart.showprices = jQuery(function($) {
    jQuery(document).ready(function( $ ) {

        if ( $("#show_prices").is(":checked") ) {
            $("#show_hide_prices").show();
        } else {
            $("#show_hide_prices").hide();
        }
        $("#show_prices").click(function() {
            if ( $("#show_prices").is(":checked") ) {
                $("#show_hide_prices").show();
            } else {
                $("#show_hide_prices").hide();
            }
        });
    });
});



(function ($) {
	
	
    var methods = {

        tabs:function (cookie) {
            var tabscount = this.find('div.tabs').length;
            if ($.cookie(cookie) == null || cookie == "product0" || tabscount == 1) var idx = 0;
            else var idx = $.cookie(cookie);
            if (idx == null) idx = 0;
            var options = { path:'/', expires:2},
                list = '<ul id="tabs">';

            var tabswidth = 100 / tabscount;
            this.find('div.tabs').each(
                function (i) {
                    list += '<li style="width:' + tabswidth + '%"><span>' + $(this).attr('title') + '</span></li>';
                    $(this).removeAttr('title');
                }
            );
            this.prepend(list + '</ul>');
            this.children('div').hide();
            // select & open menu
            var li = $('#tabs li'),
                div = this.children('div');
            li.eq(idx).addClass('current');
            div.eq(idx).slideDown(1000);

            li.click(
                function () {
                    if ($(this).not(".current")) {
                        var idx = li.index(this);
                        oldIndex = $(this).addClass("current").siblings('li.current').removeClass("current").index();
                        if (oldIndex !== -1) {
                            if (cookie !== "") $.cookie(cookie, idx, options);
                            div.eq(idx).slideDown(0);
                            div.eq(oldIndex).slideUp(0);
                        }
                    }
                }
            );
            return this;
        },
        
             accordeon:function () {
            var idx = $.cookie('accordeon'),
                options = { path:'/', expires:2},
                div = this.children('div') ,
                h3 = this.children('h3'),
                A = this.find('.menu-list a');
            if (idx == null) idx = 0;
            div.hide();
            h3.eq(idx).addClass('current');
            div.eq(idx).show();

            h3.click(
                function () {
                    var menu = $(this);
                    if (menu.not(".current")) {
                        menu.siblings('h3.current').removeClass("current").next().slideUp(200);
                        menu.addClass("current").next().slideDown(200);
                        $.cookie('accordeon', h3.index(this), options);
                    }
                }
            );
            A.click(
                function () {
                    $.cookie('vmapply', '0', options);
                }
            );
        },

        tips:function (image) {
            var xOffset = -20; // x distance from mouse
            var yOffset = 10; // y distance from mouse
            tip = this;
            tip.bind().hover(
                function (e) {
                    //a kind of sanitizing the input
                    tip.t = $('<div/>').text(this.title).html();
                    //tip.t = this.title;
                    this.title = '';
                    tip.top = (e.pageY + yOffset);
                    tip.left = (e.pageX + xOffset);
                    $('body').append('<p id="vtip"><img id="vtipArrow" /><B>' + $(this).html() + '</B><br/ >' + tip.t + '</p>');
                    $('#vtip #vtipArrow').attr("src", image);
                    $('#vtip').css("top", tip.top + "px").css("left", tip.left + "px").fadeIn("slow");
                },
                function () {
                    this.title = tip.t;
                    $("#vtip").fadeOut("slow").remove();
                }
            ).mousemove(
                function (e) {
                    tip.top = (e.pageY + yOffset);
                    tip.left = (e.pageX + xOffset);
                    $("#vtip").css("top", tip.top + "px").css("left", tip.left + "px");
                }
            ).mousedown(
                function (e) {
                    this.title = tip.t;
                    $("#vtip").fadeOut("slow").remove();
                }
            ).mouseup(
                function (e) {
                    this.title = tip.t;
                    $("#vtip").fadeOut("slow").remove();
                }
            );

        },

        

    };

    $.fn.vm2admin = function (method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on Vm2 admin jQuery library');
        }

    };
})(jQuery);
