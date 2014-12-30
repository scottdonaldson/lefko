jQuery(document).ready(function($){

	function makeMobile() {
		$('html').removeClass('no-touch').addClass('touch');
	}
	$(window).keydown(function(e) {
		if ( e.keyCode === 84 ) { // t
			makeMobile();
		}
	})

	// Declare variables
	var win = $(window),
		header = $('header'),
		siteTitle = $('#site-title'),
		menuItem = $('.standard .menu li a'),
		mobileMenuItem = $('.mobile .menu li a'),
		body = $('body'),
		touchBody = $('.touch body'),
		menuImage = menuItem.find('.menu-description'),
		subMenu = $('.sub-menu');

	/* --------- NAVIGATION MENU ------------ */

	// Background images for those that have them
	menuImage.closest('a').addClass('image');

	// For all links that go to another page,
	// add class of 'is-link' (show line
	// extending to right for cladogram on click)
	menuItem.each(function(){
		var $this = $(this);
		if ($this.attr('href')[0] !== '#') {
			$this.parent('li').addClass('is-link');
		}

		// Background images appear on hover and text disappears
		var text = $this.html();

		if ($this.hasClass('image')) {
			$this.mouseenter(function(){
				var $this = $(this),
					height = $this.closest('li').height();

				$this.css({
					'background-image': 'url(' + $this.find('.menu-description').text() + ')',
					'height': height
					}).html('&nbsp;');
			}).mouseleave(function(){
				$(this).css({
					'background-image': 'none',
					'height': 'auto'
				}).html(text);
			});
		}
	});

	// Wrap submenus to help with styling
	var wrapper = $('<div class="wrapper"></div>');
	subMenu.wrap(wrapper);
	function wrapHeight(){
		wrapper.height(win.height());
	}
	wrapHeight();
	win.resize(wrapHeight);

	// Insert border before submenu for cladogram
	subMenu.before('<div class="border-left"></div>');

	// List submenu items as subitem-1, 2, 3, etc.
	// to set padding of its submenu
	subMenu.each(function(){
		$(this).children('li').each(function(i){
			$(this).addClass('subitem-' + (i + 1));
		});
	});

	// Opening and closing navigation (standard)
	menuItem.each(function(){
		var li = $(this).parent('li');

		// Set height of submenu
		var subHeight = li.children('.wrapper').children('.sub-menu').height();
		li.children('.wrapper')
			.find('.border-left').css({
				'height': subHeight - 37,
				'margin-bottom': -subHeight + 35
			});
	});

	// Hide all (standard) wrappers
	$('.standard .wrapper').hide();

	// Find the heights of mobile submenus
	$('.mobile .sub-menu').each(function(){
		var $this = $(this);
		var items = $this.children('li').children('a'),
			subHeight = 0;
		items.each(function(){
			subHeight += $(this).outerHeight(false) + 10;
		});

		$this.attr('data-height', subHeight);
	}).hide();

	mobileMenuItem.each(function(){

		var lastSegment = this.href.split('/');
		lastSegment = lastSegment[lastSegment.length - 1];

		if ( lastSegment[0] !== '#' ) {
			$(this).parent('li').addClass('is-link');
		}
	});

	mobileMenuItem.click(function(){
		var li = $(this).parent('li');
		// Set height of submenu
		var subHeight = li.children('.wrapper').children('.sub-menu').attr('data-height');
		var parentSubHeight = parseInt(subHeight, 10) + parseInt(li.closest('.sub-menu').closest('.sub-menu').attr('data-height'));

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

	// ----- SPACER
	var spacer = $('.spacer');
	function spacerSize(){
		spacerWidth = $('#content').outerWidth();
		spacer.width(spacerWidth);
	}
	spacerSize();
	win.resize(spacerSize);

	/* ----------- Upcoming Events ----- */
	var events = $('.events'),
		up = events.next(),
		down = up.next(),
		eventsHeight = events.find('article').length * 120,
		pos = 0;
	up.click(function(){
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
	down.click(function(){
		up.removeClass('faded');

		if (pos - 240 > -eventsHeight) {
			pos = pos - 120;
			events.find('article').animate({
				'top': pos,
			});
		} else if (pos - 240 === -eventsHeight) {
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
		thumbUnit = 55,
		thumbHeight = thumbs.find('li').length * thumbUnit,
		maxThumbHeight = 370;

	// Set height of content and center image
	// (do same on resize)
	function centerThings(){
		var cHeight = win.height() - 2 * thumbUnit,
			shown = $('.shown'),
			margin = 0.5 * (cHeight - shown.height() - (thumbUnit - 1)),
			thumbMargin = 0.5 * (cHeight - thumbsDiv.height() - (thumbUnit - 1));
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
				shown.find('img').height(cHeight - ( 2 * thumbUnit + 20 ) );
			} else {
				shown.find('img').height(cHeight - 2 * thumbUnit);
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
	win.on('load resize', centerThings);

	// Add cladogram lines for details
	details.each(function(){
		var $this = $(this);
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

	// Add class 'final' for last item in gallery
	gallery.find('li').last().addClass('final');

	// Set size of description equal to its image
	function descWidth(){
		var shown = $('.shown'),
			desc = shown.find('.description'),
			width = desc.prev('img').width(),
			padLeft = parseInt(desc.css('padding-left'), 10);

		desc.width(width - 2 * padLeft);
		if (desc.prev('img').width() < 450) {
			desc.addClass('narrow');
		}
	}
	win.on('load resize', descWidth);

	// Fade out Prev
	prev.addClass('faded');

	function moveThumbs(pos) {
		ul.stop().animate({
			top: pos || 0
		});
	}

	function goTo(degree) {
		var shown = $('.shown'),
			num,
			target,
			closedDetails = false,
			thumbHeightVis,
			liTop,
			shiftTo;

		// if there is a degree, then we're going either immediately
		// next or previous from the current shown image
		if ( degree === 1 || degree === -1 ) {
			num = shown.index() + degree;
			// -1 = prev, 1 - next...
			// eq(-1) returns the last item in the list,
			// so return false if it is that or greater than the list length
			target = ( num >= 0 && num <= thumbs.find('li').length ) ? thumbs.find('li').eq(num) : false;
		// if there is no degree, then we have clicked
		// on a thumb and the target is the clicked element
		} else {
			target = $(this);
			num = target.index();
		}

		if ( target.length > 0 ) {

			// activate target and deactivate its siblings
			target.addClass('active').siblings().removeClass('active');

			// find the corresponding image in the gallery, show it, and hide its siblings
			var toShow = gallery.find('li').eq(num);
			toShow.show().addClass('shown').siblings().removeClass('shown').hide();

			// if target is the first or last, fade out the corresponding arrow
			if ( target.hasClass('final') ) {
				next.addClass('faded');
			} else if ( target.hasClass('initial') ) {
				prev.addClass('faded');
			} else {
				next.removeClass('faded');
				prev.removeClass('faded');
			}

			// if the target is a detail
			if ( target.hasClass('detail') ) {
				// if going previous
				if ( degree === -1 ) {
					target.slideDown().prevUntil('.orig').slideDown().find('.detail-clad').show();
					target.find('.detail-clad').show();
					target.nextUntil('.orig').slideDown();
				// if going next
				} else if ( degree === 1 ) {
					target.slideDown().nextUntil('.orig').slideDown().find('.detail-clad').show();
					target.find('.detail-clad').show();
					target.prevUntil('.orig').slideDown();
				}
			} else {
				// if an original that has details, show them
				if ( target.next().hasClass('detail') ) {
					target.nextUntil('.orig').slideDown().find('.detail-clad').show();
				} else {
					thumbs.find('.detail').slideUp();
					thumbs.find('.detail-clad').hide();

					// we closed some details, so factor that in
					// when repositioning the entire list later
					closedDetails = true;
				}
			}

			// Get height of all visible thumbnails.
			// 1. get all the originals (not details) plus the number of forthcoming visible details
			thumbHeightVis = thumbs.find('.orig').length + target.nextUntil('.orig').length;
			// 2. multiply by the thumb unit
			thumbHeightVis = thumbHeightVis * thumbUnit;
			// 3. if the target is a detail, include previous details and itself
			// (multiplied by the thumb unit)
			if ( target.hasClass('detail') ) {
				thumbHeightVis += thumbUnit * ( target.prevUntil('.orig').length + 1 );
			}

			// if the visible thumb height is greater than the max thumb height, we must do things.
			// by default, though, we do nothing...
			shiftTo = 0;
			if ( thumbHeightVis > maxThumbHeight ) {

				// find out where the top of the target is
				if ( target.hasClass('detail') && target.next().hasClass('orig') ) {
					liTop = target.prevAll('.orig:first').position().top + thumbUnit;
				} else {
					liTop = target.position().top;
				}

				// only shift if is within range (to not exceed visible space)
				if ( liTop > maxThumbHeight / 2 && liTop < thumbHeightVis - maxThumbHeight / 2 ) {

					shiftTo = ( -liTop + 0.5 * ( maxThumbHeight - target.height() ) ) / thumbUnit;

				} else if ( liTop >= thumbHeightVis - 0.5 * maxThumbHeight && liTop < thumbHeightVis ) {
					shiftTo = ( -thumbHeightVis + maxThumbHeight ) / thumbUnit;

				}

				// if we're landing on an original that follows some details,
				// AND we are closing some details, then take that into account
				if ( target.hasClass('orig') && target.prev().hasClass('detail') && closedDetails ) {
					shiftTo += target.prevUntil('.orig').length;
				}

				shiftTo = Math.round(shiftTo) * thumbUnit;
			}

			moveThumbs(shiftTo);

			centerThings();
			descWidth();
		}
	}

	function goPrev() {
		goTo(-1);
	}

	function goNext() {
		goTo(1);
	}

	// Arrow keys left and right
	$(document).keydown(function(e) {
		var code = e.keyCode || e.which;
		if ( code === 37 || code === 38 ) { // previous
			e.preventDefault();
			goPrev();
		} else if ( code === 39 || code === 40 ) { // next
			e.preventDefault();
			goNext();
		}
	});

	// Click 'Prev' or 'Next'
	var interval = null;
	thumbsDiv.find('.prev').click(goPrev).mousedown(function(){
		clearInterval(interval);
		interval = setInterval(goPrev, 500);
	}).mouseup(function(){
		clearInterval(interval);
	});
	thumbsDiv.find('.next').click(goNext).mousedown(function(){
		clearInterval(interval);
		interval = setInterval(goNext, 500);
	}).mouseup(function(){
		clearInterval(interval);
	});

	// Clicking on a thumbnail
	thumbs.find('li').click(goTo);
});
