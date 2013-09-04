jQuery(document).ready(function($){

	// Declare variables
	var header = $('header'),
		siteTitle = $('#site-title'),
		menuItem = $('.standard .menu li a'),
		IEmenuItem = $('.lt-ie9 .menu li'),
		mobileMenuItem = $('.mobile .menu li a'),
		body = $('body'),
		touchBody = $('.touch body'),
		menuImage = menuItem.find('.menu-description'),
		subMenu = $('.sub-menu');

	/* --------- NAVIGATION MENU ------------ */

	// Background images for those that have them
	menuImage.each(function(){
		$this = $(this);
		var link = $this.closest('a');
		link.addClass('image');
	});

	// For all links that go to another page,
	// add class of 'is-link' (show line
	// extending to right for cladogram on click)
	menuItem.each(function(){
		$this = $(this);
		var target = $this.attr('href');
		if (target[0] != '#') {
			$this.parent('li').addClass('is-link');
		}

		// Background images appear on hover and text disappears
		var text = $this.html();		
		
		if ($this.hasClass('image')) {
			$this.on('mouseenter',function(){
				$this = $(this);
				var height = $this.closest('li').height();

				$this.css('background-image', 'url(' + $this.find('.menu-description').text() + ')').html('&nbsp;').height(height);
			}).on('mouseleave',function(){
				$(this).css('background-image', 'none').html(text);
			});
		}
	});

	// Wrap submenus to help with styling
	subMenu.wrap('<div class="wrapper"></div>');
	var wrapHeight = function(){
		$('.wrapper').height($(window).height());
	}
	wrapHeight();
	$(window).on('resize', wrapHeight);

	// Insert border before submenu for cladogram
	subMenu.before('<div class="border-left"></div>');

	// List submenu items as subitem-1, 2, 3, etc.
	// to set padding of its submenu
	subMenu.each(function(){
		$(this).children('li').each(function(i){
			$(this).addClass('subitem-'+(i+1));
		});
	});

	// Opening and closing navigation (standard)
	menuItem.each(function(){
		var li = $(this).parent('li');
		
		// Set height of submenu
		var subHeight = li.children('.wrapper').children('.sub-menu').height();
		li.children('.wrapper')
			.find('.border-left').css({
				'height': subHeight-37,
				'margin-bottom': -subHeight+35
			});	
	});

	// Hide all (standard) wrappers
	$('.standard .wrapper').hide();

	// Cladogram (IE) -- add class to help with styling :after line
	IEmenuItem.each(function(){
		$this = $(this);
		if (!$this.hasClass('is-link')) {
			$this.on('mouseover',function(){
				$this = $(this);
				$this.children('a').addClass('IE-active');
				$this.siblings('li').children('a').removeClass('IE-active');
			}).on('mouseleave',function(){
				$this = $(this);
				$this.children('a').removeClass('IE-active');
			});
		}
	});

	// Find the heights of mobile submenus
	$('.mobile .sub-menu').each(function(){
		$this = $(this);
		var items = $this.children('li').children('a'),
			subHeight = 0;
		items.each(function(){
			subHeight = subHeight + $(this).outerHeight() + 10;
		});

		$this.attr('data-height', subHeight);
	}).hide();

	mobileMenuItem.each(function(){
		$this = $(this);
		var target = $this.attr('href');
		if (target[0] != '#') {
			$this.parent('li').addClass('is-link');
		}
	});

	mobileMenuItem.on('click',function(){
		var li = $(this).parent('li');
		// Set height of submenu
		var subHeight = li.children('.wrapper').children('.sub-menu').attr('data-height');
		var parentSubHeight = parseInt(subHeight) + parseInt(li.closest('.sub-menu').closest('.sub-menu').attr('data-height'));

		// Is it already open?
		if (li.hasClass('open') && !li.hasClass('is-link')) {
			li.removeClass('open').find('.sub-menu li').removeClass('open');

			if (li.hasClass('level-0')) {
				touchBody.removeClass('big-menu');
			}
			li.find('.border-left').css({
					'height': 0,
					'margin-bottom': 0
				});
			li.find('.sub-menu').slideUp();
			
			// Reset height of parent (on mobile)
			var parentReset = li.closest('.sub-menu').attr('data-height');
			li.closest('.sub-menu').prev('.border-left').css({
				'height': parentReset - 45,
				'margin-bottom': -parentReset + 45
			});	

		// If not, let's open it
		} else {
			li.addClass('open').siblings('li').removeClass('open');
			touchBody.addClass('big-menu');	

			li.children('.wrapper')
				.children('.border-left').css({
					'height': subHeight - 45,
					'margin-bottom': -subHeight + 45
				})
				.next('.sub-menu').slideDown();
				li.siblings().find('.sub-menu').slideUp()
					.prev('.border-left').css({
						'height': 0,
						'margin-bottom': 0
					});	

				// Reset height of parent submenu
				li.closest('.sub-menu').prev('.border-left').css({
					'height': parentSubHeight - 45,
					'margin-bottom': -parentSubHeight + 45
				});	
		}
	});

		// or, for large touch screens
		/*
		} else {
			menuItem.each(function(){
				var li = $(this).parent('li');
				
				// Set height of submenu
				var subHeight = li.children('.wrapper').children('.sub-menu').height();
				li.children('.wrapper')
					.find('.border-left').css({
						'height': subHeight-35,
						'margin-bottom': -subHeight+35
					});	
			});
		}
		*/
	// });	
		
	// ----- SPACER
	var spacer = $('.spacer');
	var spacerSize = function(){
		spacerWidth = $('#content').outerWidth();
		spacer.width(spacerWidth);
	}	
	spacerSize();
	$(window).on('resize', spacerSize);

	/* ----------- Upcoming Events ----- */
	var events = $('.events'),
		up = events.next(),
		down = up.next(),
		eventsHeight = events.find('article').length * 120,
		pos = 0;
	up.on('click',function(){
		down.removeClass('faded');

		if (pos + 120 < 0) {
			pos = pos + 120;
			events.find('article').animate({
				'top': pos,
			});
		} else if (pos + 120 == 0) {
			pos = 0;
			events.find('article').animate({
				'top': pos,
			});			
			up.addClass('faded');
		}
	});
	down.on('click',function(){
		up.removeClass('faded');

		if (pos - 240 > -eventsHeight) {
			pos = pos - 120;
			events.find('article').animate({
				'top': pos,
			});
		} else if (pos - 240 == -eventsHeight) {
			pos = pos - 120;
			events.find('article').animate({
				'top': pos,
			});
			down.addClass('faded');
		}
	});


	/* ------------ Series ------------- */

	var content = $('.single #content'),
		gallery = $('#gallery'),
		thumbsDiv = $('#thumbs'),
		prev = thumbsDiv.find('.prev'),
		next = thumbsDiv.find('.next'),
		thumbs = thumbsDiv.find('ul'),
		details = thumbs.find('.detail'),
		desc = $('.description'),
		container = $('.container'),
		ul = container.find('ul'),
		thumbHeight = thumbs.find('li').length*55;

	// Set height of content and center image
	// (do same on resize)
	var centerThings = function(){
		var cHeight = $(window).height()-110,
			shown = $('.shown'),
			margin = .5*(cHeight - shown.height()-54),
			thumbMargin = .5*(cHeight - thumbsDiv.height()-54);
		content.height(cHeight);
		shown.find('img').css({
			'height': 'auto',
			'width': 'auto'
		});
		if (shown.height() > cHeight - 40) {
			// Sites and Spaces is postid-890...
			// page has reeeally long descriptions
			// Also Corrugated drawings (postid-61)
			if (body.hasClass('postid-890') || body.hasClass('postid-61')) {
				shown.find('img').height(cHeight - 130);
			} else {
				shown.find('img').height(cHeight - 110);
			}
		}			
		// No negative margins
		if (margin >= 0) {
			shown.css('margin-top', margin);
		}
		if (thumbMargin >= 0) {
			thumbsDiv.css('margin-top', thumbMargin);
		}
	}
	$(window).on('load', centerThings).on('resize', centerThings);

	// Add cladogram lines for details
	details.each(function(){
		$this = $(this);
		// Is this the first one? If not, prepend the div
		// with class of 'not-first' (to make taller)
		if ($this.prev('.detail').hasClass('detail')){
			$this.prepend('<div class="detail-clad not-first"></div>');
		} else {
			$this.prepend('<div class="detail-clad"></div>');
		}
	});

	// Make first thumbnail active
	thumbs.find('li').first().addClass('active initial');
	thumbs.find('li').last().addClass('final');

	// Hide all details...
	details.hide();
	// ... except if the first thumbnail has details
	thumbs.find('li').first().nextUntil('.orig').show();

	// When clicking on a thumbnail, show its
	// corresponding image
	thumbs.find('li').on('click',function(){
		$this = $(this);
		$this.addClass('active').siblings().removeClass('active');
		var num = $this.data('image'),
			li = $('#gallery li[data-image='+num+']');

		li.addClass('shown').show().find('.description').width(li.find('img').width());
		li.siblings().hide().removeClass('shown');
	});	

	// Add class 'final' for last item in gallery
	gallery.find('li').last().addClass('final');
	
	// Set size of description equal to its image
	var descWidth = function(){
		var shown = $('.shown'),
			desc = shown.find('.description');
		desc.width(desc.prev('img').width());
		if (desc.prev('img').width() < 450) {
			desc.addClass('narrow');
		}
	}
	$(window).on('load',descWidth).on('resize',descWidth);

	// Fade out Prev
	prev.addClass('faded');

	var goPrev = function(){
		next.removeClass('faded');

		var shown = $('.shown');
		// don't go back if on first
		if (!shown.hasClass('initial')) {
			shown.removeClass('shown').hide().prev().show().addClass('shown');

			// Thumbnail
			var num = shown.prev().data('image'),
				target = thumbs.find('li[data-image='+num+']');
				target.addClass('active').siblings().removeClass('active');

			if (target.hasClass('initial')) {
				prev.addClass('faded');
			}	

			if (target.hasClass('detail')) {
				target.slideDown().prevUntil('.orig').slideDown().find('.detail-clad').show();
				target.find('.detail-clad').show();
				target.nextUntil('.orig').slideDown();
			}	
			if (target.next().hasClass('orig')) {
				target.next().nextUntil('.orig').slideUp().find('.detail-clad').hide();
			}

			// Get height of all visible thumbnails
			if (target.hasClass('orig')) {
				var thumbHeightVis = (thumbs.find('li').not('.detail').length + target.nextUntil('.orig').length)*55;
			} else {
				var thumbHeightVis = (thumbs.find('li').not('.detail').length + target.nextUntil('.orig').length + target.prevUntil('.orig').length + 1)*55;
			}

			if (thumbHeightVis > 370) {					
				if (target.hasClass('detail') && target.next().hasClass('orig')) {
					var liTop = target.prevAll('.orig:first').position().top + target.prevUntil('.orig').length*55;
				} else {
					var liTop = target.position().top;
				}

				// only shift if is within range (to not exceed visible space)
				if (liTop > 185 && liTop < thumbHeightVis-185) {
					ul.stop().animate({
						'top': Math.round((-liTop+185-$this.height()/2)/55)*55,
					});
				} else if (liTop >= thumbHeightVis-185 && liTop < thumbHeightVis ) { 
					ul.stop().animate({
						'top': Math.round((-thumbHeightVis+370)/55)*55,
					});
				} else {
					ul.stop().animate({
						'top': 0,
					});
				}
			} else {
				ul.stop().animate({
					'top': 0,
				})
			}
		}
		centerThings();
		descWidth();
	}
	var goNext = function(){
		prev.removeClass('faded');

		var shown = $('.shown');
		// don't go forward if on last
		if (!shown.hasClass('final')) {
			shown.removeClass('shown').hide().next().show().addClass('shown');

			// Thumbnail
			var num = shown.next().data('image'),
				target = thumbs.find('li[data-image='+num+']');
				target.addClass('active').siblings().removeClass('active');

			if (target.hasClass('final')) {
				next.addClass('faded');
			}	

			if (target.next().hasClass('detail')) {
				target.nextUntil('.orig').slideDown().find('.detail-clad').show();
			} 
			if (target.hasClass('orig')) {
				target.prevAll('.detail').slideUp().find('.detail-clad').hide();
			}	
			// Get height of all visible thumbnails
			if (target.hasClass('orig')) {
				var thumbHeightVis = (thumbs.find('li').not('.detail').length + target.nextUntil('.orig').length)*55;
			} else {
				var thumbHeightVis = (thumbs.find('li').not('.detail').length + target.nextUntil('.orig').length + target.prevUntil('.orig').length + 1)*55;
			}

			if (thumbHeightVis > 370) {			
				if (target.hasClass('orig') && target.prev().hasClass('detail')) {
					var liTop = target.prevAll('.orig:first').position().top + 55;
				} else {
					var liTop = target.position().top;
				}	

				// only shift if is within range (to not exceed visible space)
				if (liTop > 185 && liTop < thumbHeightVis-185) {
					ul.stop().animate({
						'top': Math.round((-liTop+185-$this.height()/2)/55)*55,
					});
				} else if (liTop >= thumbHeightVis-185 && liTop < thumbHeightVis ) { 
					ul.stop().animate({
						'top': Math.round((-thumbHeightVis+370)/55)*55,
					});
				} else {
					ul.stop().animate({
						'top': 0,
					});
				}
			} else {
				ul.stop().animate({
					'top': 0,
				})
			}
		}	
		centerThings();
		descWidth();
	}

	// Arrow keys left and right
	$(document).on('keydown',function(e){
		if (e.keyCode == 37) { // previous
			e.preventDefault();
			goPrev();
		} else if (e.keyCode == 38) { // previous
			e.preventDefault();
			goPrev();
		} else if (e.Keycode == 39) { // next
			e.preventDefault();
			goNext();
		} else if (e.keyCode == 40) { // next
			e.preventDefault();
			goNext();
		}
	});
	// Click 'Prev' or 'Next'
	var interval = null;
	thumbsDiv.find('.prev').on('click',function(){
		goPrev();
	}).on('mousedown',function(){
		clearInterval(interval);
		interval = setInterval(function(){
			goPrev();
		}, 500);
	}).on('mouseup',function(){
		clearInterval(interval);
	});
	thumbsDiv.find('.next').on('click',function(){
		goNext();
	}).on('mousedown',function(){
		clearInterval(interval);
		interval = setInterval(function(){
			goNext();
		}, 500);
	}).on('mouseup',function(){
		clearInterval(interval);
	});
	
	// Clicking on a thumbnail
	thumbs.find('li').on('click',function(){
		$this = $(this);

		// Is this a detail?
		if ($this.hasClass('detail')) {
			$this.nextUntil('.orig').slideDown();
			$this.prevAll('.orig:first').prevAll('.detail').slideUp();
		// Is this an original with details?
		} else if ($this.next().hasClass('detail')) {
			$this.nextUntil('.orig').slideDown();
			$this.prevAll('.detail').slideUp();
		// Otherwise hide all details
		} else {
			details.slideUp();
		}
		
		// Get height of all visible thumbnails
		if ($this.hasClass('detail')) {
			var thumbHeightVis = (thumbs.find('li').not('.detail').length + $this.prevUntil('.orig').length + $this.nextUntil('.orig').length + 1)*55;
		} else {
			var thumbHeightVis = (thumbs.find('li').not('.detail').length + $this.nextUntil('.orig').length)*55;
		}

		if (thumbHeightVis > 370) {					
			if ($this.hasClass('orig') && $this.prev().hasClass('detail')) {
				var liTop = $this.prevAll('.orig:first').position().top + 55;
			} else {
				var liTop = $this.position().top;
			}	
			// only shift if is within range (to not exceed visible space)
			if (liTop > 185 && liTop < thumbHeightVis-185) {
				ul.stop().animate({
					'top': Math.round((-liTop+185-$this.height()/2)/55)*55,
				});
			} else if (liTop >= thumbHeightVis-185 && liTop < thumbHeightVis ) { 
				ul.stop().animate({
					'top': Math.round((-thumbHeightVis+370)/55)*55,
				});
			} else {
				ul.stop().animate({
					'top': 0,
				});
			} 
		} else {
				ul.stop().animate({
					'top': 0,
				})
			}
		if ($this.hasClass('final')) {
			next.addClass('faded');
		} else if ($this.hasClass('initial')) {
			prev.addClass('faded');
		} else {
			next.removeClass('faded');
			prev.removeClass('faded');
		}
		centerThings();
		descWidth();
	});
});