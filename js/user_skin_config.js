
    // Config box

    // Enable/disable fixed top navbar
    $('#fixednavbar').click(function () {
        if ($('#fixednavbar').is(':checked')) {
            $(".navbar-static-top").removeClass('navbar-static-top').addClass('navbar-fixed-top');
            $("body").removeClass('boxed-layout');
            $("body").addClass('fixed-nav');
            $('#boxedlayout').prop('checked', false);

            if (localStorageSupport) {
                localStorage.setItem("boxedlayout",'off');
            }

            if (localStorageSupport) {
                localStorage.setItem("fixednavbar",'on');
            }
			
			$.get('user_skin_config.php?set_site_preferences=top_navbar&value=on');
			
        } else {
            $(".navbar-fixed-top").removeClass('navbar-fixed-top').addClass('navbar-static-top');
            $("body").removeClass('fixed-nav');

            if (localStorageSupport) {
                localStorage.setItem("fixednavbar",'off');
            }
			$.get('user_skin_config.php?set_site_preferences=top_navbar&value=off');
        }
    });

    // Enable/disable fixed sidebar
    $('#fixedsidebar').click(function () {
        if ($('#fixedsidebar').is(':checked')) {
            $("body").addClass('fixed-sidebar');
            $('.sidebar-collapse').slimScroll({
                height: '100%',
                railOpacity: 0.9
            });

            if (localStorageSupport) {
                localStorage.setItem("fixedsidebar",'on');
            }
			$.get('user_skin_config.php?set_site_preferences=fixed_sidebar&value=on');
        } else {
            $('.sidebar-collapse').slimscroll({destroy: true});
            $('.sidebar-collapse').attr('style', '');
            $("body").removeClass('fixed-sidebar');

            if (localStorageSupport) {
                localStorage.setItem("fixedsidebar",'off');
            }
			$.get('user_skin_config.php?set_site_preferences=fixed_sidebar&value=off');
        }
    });

    // Enable/disable collapse menu
    $('#collapsemenu').click(function () {
        if ($('#collapsemenu').is(':checked')) {
            $("body").addClass('mini-navbar');
            SmoothlyMenu();

            if (localStorageSupport) {
                localStorage.setItem("collapse_menu",'on');
            }
			$.get('user_skin_config.php?set_site_preferences=collapse_menu&value=on');

        } else {
            $("body").removeClass('mini-navbar');
            SmoothlyMenu();

            if (localStorageSupport) {
                localStorage.setItem("collapse_menu",'off');
            }
			$.get('user_skin_config.php?set_site_preferences=collapse_menu&value=off');
        }
    });

    // Enable/disable boxed layout
    $('#boxedlayout').click(function () {
        if ($('#boxedlayout').is(':checked')) {
            $("body").addClass('boxed-layout');
            $('#fixednavbar').prop('checked', false);
            $(".navbar-fixed-top").removeClass('navbar-fixed-top').addClass('navbar-static-top');
            $("body").removeClass('fixed-nav');
            $(".footer").removeClass('fixed');
            $('#fixedfooter').prop('checked', false);

            if (localStorageSupport) {
                localStorage.setItem("fixednavbar",'off');
            }

            if (localStorageSupport) {
                localStorage.setItem("fixedfooter",'off');
            }


            if (localStorageSupport) {
                localStorage.setItem("boxedlayout",'on');
            }
			$.get('user_skin_config.php?set_site_preferences=boxed_layout&value=on');
        } else {
            $("body").removeClass('boxed-layout');

            if (localStorageSupport) {
                localStorage.setItem("boxedlayout",'off');
            }
			$.get('user_skin_config.php?set_site_preferences=boxed_layout&value=off');
        }
    });

    // Enable/disable fixed footer
    $('#fixedfooter').click(function () {
        if ($('#fixedfooter').is(':checked')) {
            $('#boxedlayout').prop('checked', false);
            $("body").removeClass('boxed-layout');
            $(".footer").addClass('fixed');

            if (localStorageSupport) {
                localStorage.setItem("boxedlayout",'off');
            }

            if (localStorageSupport) {
                localStorage.setItem("fixedfooter",'on');
            }
			$.get('user_skin_config.php?set_site_preferences=fixed_footer&value=on');
        } else {
            $(".footer").removeClass('fixed');

            if (localStorageSupport) {
                localStorage.setItem("fixedfooter",'off');
            }
			$.get('user_skin_config.php?set_site_preferences=fixed_footer&value=off');
        }
    });

    // SKIN Select
    $('.spin-icon').click(function () {
        $(".theme-config-box").toggleClass("show");
    });

    // Default skin
    $('.s-skin-0').click(function () {
        $("body").removeClass("skin-1");
        $("body").removeClass("skin-2");
        $("body").removeClass("skin-3");
		$.get('user_skin_config.php?set_site_preferences=skins&value=default');
    });

    // Blue skin
    $('.s-skin-1').click(function () {
        $("body").removeClass("skin-2");
        $("body").removeClass("skin-3");
        $("body").addClass("skin-1");
		$.get('user_skin_config.php?set_site_preferences=skins&value=blue');
    });

    // Inspinia ultra skin
    $('.s-skin-2').click(function () {
        $("body").removeClass("skin-1");
        $("body").removeClass("skin-3");
        $("body").addClass("skin-2");
		$.get('user_skin_config.php?set_site_preferences=skins&value=ultra');
    });

    // Yellow skin
    $('.s-skin-3').click(function () {
        $("body").removeClass("skin-1");
        $("body").removeClass("skin-2");
        $("body").addClass("skin-3");
		$.get('user_skin_config.php?set_site_preferences=skins&value=yellow');
    });
	//Notifications Positions
	
    $('#topright').click(function () {
        
		$.get('user_skin_config.php?set_site_preferences=notifications&value=toast-top-right');
    });
	
	$('#bottomright').click(function () {
        
		$.get('user_skin_config.php?set_site_preferences=notifications&value=toast-bottom-right');
    });
	$('#bottomleft').click(function () {
        
		$.get('user_skin_config.php?set_site_preferences=notifications&value=toast-bottom-left');
    });
	$('#topleft').click(function () {
        
		$.get('user_skin_config.php?set_site_preferences=notifications&value=toast-top-left');
    });
	$('#topfullwidth').click(function () {
        
		$.get('user_skin_config.php?set_site_preferences=notifications&value=toast-top-full-width');
    });
	$('#bottomfullwidth').click(function () {
        
		$.get('user_skin_config.php?set_site_preferences=notifications&value=toast-bottom-full-width');
    });
	$('#topcenter').click(function () {
        
		$.get('user_skin_config.php?set_site_preferences=notifications&value=toast-top-center');
    });
	$('#bottomcenter').click(function () {
        
		$.get('user_skin_config.php?set_site_preferences=notifications&value=toast-bottom-center');
    });
	
	
  if (!localStorageSupport) {
        var collapse = localStorage.getItem("collapse_menu");
        var fixedsidebar = localStorage.getItem("fixedsidebar");
        var fixednavbar = localStorage.getItem("fixednavbar");
        var boxedlayout = localStorage.getItem("boxedlayout");
        var fixedfooter = localStorage.getItem("fixedfooter");

        if (collapse == 'on') {
            $('#collapsemenu').prop('checked','checked')
        }
        if (fixedsidebar == 'on') {
            $('#fixedsidebar').prop('checked','checked')
        }
        if (fixednavbar == 'on') {
            $('#fixednavbar').prop('checked','checked')
        }
        if (boxedlayout == 'on') {
            $('#boxedlayout').prop('checked','checked')
        }
        if (fixedfooter == 'on') {
            $('#fixedfooter').prop('checked','checked')
        }
    }
	else{
			$.get('user_skin_config.php?operation=get_site_preferences', function (d) {
			
			/*if (d.lockscreen_timeout != 0) {
				
				var slider = $("#auto_lock").data("ionRangeSlider");
				slider.update({
				
				from: d.lockscreen_timeout,
				
			});
			}*/	
			var location_2 = "<h3>" + d.location_2 + "</h3>";
			$("#location_2").html(location_2);
			if (d.collapse_menu == 'on') {
				$('#collapsemenu').prop('checked','checked');
				$("body").addClass('mini-navbar');
				SmoothlyMenu();
			}
			if (d.fixed_sidebar == 'on') {
				$('#fixedsidebar').prop('checked','checked');
				$("body").addClass('fixed-sidebar');
				$('.sidebar-collapse').slimScroll({
					height: '100%',
					railOpacity: 0.9
				});
			}
			if (d.top_navbar == 'on') {
				$('#fixednavbar').prop('checked','checked');
				$(".navbar-static-top").removeClass('navbar-static-top').addClass('navbar-fixed-top');
				$("body").removeClass('boxed-layout');
				$("body").addClass('fixed-nav');
				$('#boxedlayout').prop('checked', false);
			}
			if (d.boxed_layout == 'on') {
				$('#boxedlayout').prop('checked','checked');
				$("body").addClass('boxed-layout');
				$('#fixednavbar').prop('checked', false);
				$(".navbar-fixed-top").removeClass('navbar-fixed-top').addClass('navbar-static-top');
				$("body").removeClass('fixed-nav');
				$(".footer").removeClass('fixed');
				$('#fixedfooter').prop('checked', false);
			}
			if (d.fixed_footer == 'on') {
				$('#fixedfooter').prop('checked','checked');
				 $('#boxedlayout').prop('checked', false);
            $("body").removeClass('boxed-layout');
            $(".footer").addClass('fixed');
			}
			switch(d.skins){
				case 'default':
		
					$("body").removeClass("skin-1");
					$("body").removeClass("skin-2");
					$("body").removeClass("skin-3");
					
					break;
				case 'blue':
					$("body").removeClass("skin-2");
					$("body").removeClass("skin-3");
					$("body").addClass("skin-1");
					
					break;
				case 'ultra':
					$("body").removeClass("skin-1");
					$("body").removeClass("skin-3");
					$("body").addClass("skin-2");
					
					break;
				case 'yellow':
					$("body").removeClass("skin-1");
					$("body").removeClass("skin-2");
					$("body").addClass("skin-3");
					
					break;
					
				default:
					$("body").removeClass("skin-2");
					$("body").removeClass("skin-3");
					$("body").addClass("skin-1");
					
					break;
				
			}
			switch(d.notification_position){
				case 'toast-top-right':
					$('#topright').prop('checked','checked');
					break;
				case 'toast-bottom-right':
					$('#bottomright').prop('checked','checked');
					break;
				case 'toast-bottom-left':
					$('#bottomleft').prop('checked','checked');
					break;
				case 'toast-top-left':
					$('#topleft').prop('checked','checked');
					break;
				case 'toast-top-full-width':
					$('#topfullwidth').prop('checked','checked');
					break;
				case 'toast-bottom-full-width':
					$('#bottomfullwidth').prop('checked','checked');
					break;
				case 'toast-top-center':
					$('#topcenter').prop('checked','checked');
					break;
				case 'toast-bottom-center':
					$('#bottomcenter').prop('checked','checked');
					break;
				
			}
			
		});
	
	}
