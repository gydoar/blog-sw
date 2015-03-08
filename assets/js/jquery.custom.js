//=========================================================================================
//! /*-----------------------------------------------------------------------------------
//
//   	Custom JS for our theme
//
//  -----------------------------------------------------------------------------------*/
//=========================================================================================
jQuery.fn.reverse = [].reverse;

;(function($) {
	"use strict";

	$(document).ready(function($) {

		// Globals
		var $window = $(window),
			winWidth = $window.width(),
			winHeight = $window.height(),

			// get the height before the scroll script runs, so it can be passed to featuredImg();
			entryTitleHeight = $('.post-inner .entry-title, .page-inner .entry-title').outerHeight(); 
					
		$window.resize(function () {
			winWidth = $window.width();
			winHeight = $window.height();
		});

//==========================
//! Main Navigation Toggle
//==========================
		(function () {
			var $container = $('#container');

			// Close the nav when the pusher is clicked
			$('.site-pusher').on('click', function() {
				if ($container.hasClass('nav-open')) {
					$container.removeClass('nav-open');
					$('.site-navigation').find('.site-name, .container-menu, .site-meta').removeClass('animate-in');
				}
			});

			// Open / close the nav using the toggle button. Add a min height in case the menu is longer than the page content
			$('.nav-toggle').on('click', function () {
				$container.toggleClass('nav-open');
				
				setSiteHeight();
				
				$('.site-navigation').find('.site-name, .container-menu, .site-meta').toggleClass('animate-in');
			});
		})();
		
		
		function setSiteHeight() {
			var siteHeight = $('.site').outerHeight(),
				menuHeight = $('.nav-inner').outerHeight();

			if (menuHeight > siteHeight && $('.site').hasClass('nav-open')) {
				$('.site-pusher').css({
					minHeight: menuHeight
				});
			} else {
				$('.site-pusher').css({
					minHeight: $window.height()
				});
			}
		}
		
		setSiteHeight();
		
		$window.resize(function () {
			setSiteHeight();
		});

//==========================
//! Main Navigation Scroll
//==========================		
		function fixedMenu () {
			var $nav = $('.nav-inner'),
				lastScrollTop = 0;
			
			$window.on('scroll.navMenu', function () {
				var winHeight = $(this).height(),
					navHeight = $nav.height(),
					navTopPos,
					maxScroll = navHeight - winHeight,
					st = $(this).scrollTop(),
					scrollAmount = $(this).scrollTop();
											
				/* If the menu is taller than the window and visible */
				if ( (navHeight > winHeight) && $('.site').hasClass('nav-open') ) {
					
					/* Amount we've scrolled */
					scrollAmount = lastScrollTop - scrollAmount;
															
					/* Add the amount we've scrolled to the top pos */
					$nav.css({
						top: '+=' + scrollAmount,
						//paddingBottom: $('.site-meta').outerHeight()
					});
					
					// Get the top value after it's updated
					navTopPos = parseInt($nav.css('top'));
					
					/* Don't scroll beyond the bottom or top limits of the menu */
					/* The second conditon checks for 'overscroll' that occurs in Chrome / Safari */
					if ( navTopPos < -maxScroll || (st + winHeight) >= $('.site').outerHeight() ) {
						// bottom
						$nav.css({
							top: -maxScroll + 'px'
						});
					} else if ( navTopPos > 0 || st <= 0 ) {
						// top
						$nav.css({
							top: 0
						});
					}
	
				}
				
				// Update lastScrollTop as we scroll
				lastScrollTop = st;
	
			});	
		}
		
		if ($('.no-touch').length) {
			fixedMenu();	
		}
		

//==========================
//! Nav Effects
//==========================
		
		// Nav bar drop down
		$('.nav-bar ul .menu-item-has-children, .nav-bar ul .page_item_has_children')
			.on('mouseenter', function () {
				var $this = $(this),
					listCount = $this.children('.sub-menu, .children').children('li').length;
				
				// Slide the menu down as the nav items transition in
				$this.children('.sub-menu, .children').slideDown(listCount*50).children('li').each(function (i) {
					var $navItem = $(this);
					setTimeout(function () {
						$navItem.addClass('animate-in');
					}, (i+1)*100);
				});
			})
			.on('mouseleave', function () {
				var $this = $(this),
					listCount = $this.children('.sub-menu, children').children('li').length;
				
				// Delay sliding the menu up as the last nav item transitions out
				setTimeout(function () {
					$this.children('.sub-menu, .children').slideUp(listCount*50);
				}, 100);
				
				// Reverse transition the nav items out	
				$this.children('.sub-menu, .children').children('li').reverse().each(function (i) {
					var $navItem = $(this);
					setTimeout(function () {
						$navItem.removeClass('animate-in');
					}, i*100);
				});	
			});

//==========================
//! Fixed Top / Bottom Bar
//==========================

		// Create callbacks for scrolling direction
		function scrollDirection(Direction) {
			var lastScrollTop = 0,
				delta = 5;

			$window.scroll(function(){
				var st = $(this).scrollTop();

				//return if scroll hasn't met delta threshold
				if (Math.abs(lastScrollTop - st) <= delta) {
					return;
				}

				//direction conditions
				if (st > lastScrollTop){
					Direction.down();
				} else {
					Direction.up();
				}
				
				lastScrollTop = st;
			});
		}

		// Move the bar up and down depending on scroll direction and position
		function navScroll() {
			var $header = $('.site-header'),
				hH = $header.outerHeight(),
				topPos = ($('.admin-bar').length && winWidth > 768) ? '32px' : 0;

			scrollDirection({
				down: function () {
					$header.removeClass('fixed').css({ top: -hH });
					
					// Correct position for overscroll on Chrome / Safari with trackpad
					if ($window.scrollTop() <= 0) {
						$header.removeClass('fixed').css({ top: topPos });
					}
				},
				up: function () {
					$header.addClass('fixed').css({ top: topPos });
					
					//remove BG styles and show bar at top of page
					if ($window.scrollTop() < hH) {
						$header.removeClass('fixed').attr('style', '');
					}
				}
			});

			//default in case we load part way down the page
			if ($window.scrollTop() > hH) {
				$header.addClass('fixed').css({ top: topPos });
			}
		}
		navScroll();

		/* footer bar */
		//Going down
		$('.single-post .site-main .entry-header').waypoint(function (direction) {
			if (direction === 'down') {
				$('.footer-bar').addClass('show');
			}
		}, { offset: -61 });

		//Going up
		$('.single-post .site-main .entry-header').waypoint(function (direction) {
			if (direction === 'up') {
				$('.footer-bar').removeClass('show');
			}
		});
		
		// Push the bar up when we get to the bottom of the page so the footer is visible.
		$window.scroll(function () {
			var st = $(this).scrollTop(),
				pageHeight = $('.site-pusher').height(),
				footerHeight = $('.site-footer').outerHeight(),
				stickyOffset = (pageHeight-winHeight)-footerHeight;
			
			if (st > stickyOffset) {
				$('.footer-bar').css('bottom', footerHeight+'px');
			} else {
				$('.footer-bar').attr('style', '');
			}
		});

//=====================================================
//! Page Scroll
//=====================================================
		function scrollToContent(target) {
			if (target === '#') {
				target = $('body');
			} else {
				target = $(target);
			}
			$('html, body').animate({
				scrollTop: target.offset().top
			}, 'slow');
		}

		$('.btn-scroll').on('click', function (e) {
			e.preventDefault();
			//console.log($(this).attr('href'));
			scrollToContent($(this).attr('href'));
		});


//=============================
//! Responsive Featured Image
//=============================
		function featuredImg(entryTitleHeight) {
			var hH = entryTitleHeight + 95,
				imgH = function () {
					if ($('.format-standard, .format-link, .format-quote').length) {
						return $window.height() - 61; //height of top share bar
					} else {
						return $window.height();
					}
				};

			// Move the .post-inner up so that it overlays large featured images 
			if ($('.has-post-thumbnail.single-format-standard, .has-post-thumbnail.single-format-link, .has-post-thumbnail.single-format-quote, .has-post-thumbnail.page').length) {
				$('.post-inner, .page-inner').css({
					marginTop: -hH,
					paddingTop: hH
				});
			}

			// Set the max height for the large featured image, so that the meta bar is always visible.
			$('.single-format-standard, .single-format-link, .single-format-quote').find('.entry-thumbnail').css({
				maxHeight: imgH
			});
			
			// Fade in the image
			$('.single-post .entry-thumbnail, .page .entry-thumbnail').addClass('fade-in');
		}

		featuredImg(entryTitleHeight);

		$window.resize(function () {
			entryTitleHeight = $('.post-inner .entry-title, .page-inner .entry-title').outerHeight();
			featuredImg(entryTitleHeight);
		});

//=================
//! Social Shares
//=================
		function socialShares() {
			$('.share-links').each(function () {
				var url = $(this).parent().data('url'),
					media = $(this).parent().data('media'),
					description = $(this).parent().data('description'),
					shareText = zillaVersed.sharesText;
					
				$(this).sharrre({
					share: {
						googlePlus: true,
						facebook: true,
						twitter: true,
						pinterest: true
					},
					url: url,
					urlCurl: zillaVersed.vendorFolder + '/sharrre.php',
					template: '<span class="total-shares"><span>{total}</span> ' + shareText + '</span> <a href="#" class="share-link fa fa-twitter"></a><a href="#" class="share-link fa fa-facebook"></a><a href="#" class="share-link fa fa-google-plus"></a><a href="#" class="share-link fa fa-pinterest"></a>',
					enableHover: false,
					enableTracking: false, //TODO add as theme option
					render: function(api, options){
						options.buttons.pinterest.media = media;
						options.buttons.pinterest.description = description + ' ' + url;
						
						$(api.element).on('click', '.fa-twitter', function() {
							api.simulateClick();
							api.openPopup('twitter');
						});
						$(api.element).on('click', '.fa-facebook', function() {
							api.simulateClick();
							api.openPopup('facebook');
						});
						$(api.element).on('click', '.fa-google-plus', function() {
							api.simulateClick();
							api.openPopup('googlePlus');
						});
						$(api.element).on('click', '.fa-pinterest', function() {
							api.simulateClick();
							api.openPopup('pinterest');
						});
					}
				});
			});
		}
		
		socialShares();
		
		$( document.body ).on( 'post-load', function () {
			socialShares();
			
			responsiveVideo();
		});

//=====================================================
//! Galleries
//=====================================================
		function initSlideshows() {
			if( $().cycle ) {
				var $gallery = $('.zilla-gallery-container .zilla-gallery, .modal .zilla-gallery');
				$gallery.each(function() {
					var $this = $(this),
						timeout = $this.data('gallery-timeout'),
						fx = $this.data('gallery-fx');

					$this.cycle({
						autoHeight: 0,
						slides: '> picture',
						swipe: true,
						next:  $(this).find('.gallery-controls .cycle-next'),
						prev:  $(this).find('.gallery-controls .cycle-prev'),
						pager: $(this).find('.gallery-controls .cycle-pager'),
						timeout: timeout,
						fx: fx,
						//loader: 'wait'
					}).on('cycle-update-view', function (event, optionHash, slideOptionsHash, currentSlideEl) {
						var title = $(currentSlideEl).find('img').data('title'),
							desc = $(currentSlideEl).find('img').data('desc');			

						$this.next('.zilla-gallery-caption').find('.title').text(title).next('.description').text(desc);
						
						if (title === '' && desc === '') {
							$this.next('.zilla-gallery-caption').hide();
						} else {
							$this.next('.zilla-gallery-caption').show();
						}
						
						if (desc === '' || title === '') {
							$this.next('.zilla-gallery-caption').find('.description:before').hide();
						} else {
							$this.next('.zilla-gallery-caption').find('.description:before').show();
						}
					}).on('cycle-before', function () {
						$this.next('.zilla-gallery-caption').addClass('fade-out');
					}).on('cycle-after', function () {
						$this.next('.zilla-gallery-caption').removeClass('fade-out');
					});		
					
					$(document.documentElement).keyup(function (event) {
						if (event.keyCode === 37) {
							$this.cycle('prev');
						} else if (event.keyCode === 39) {
							$this.cycle('next');
						}
					});			
				});
			}
		}
		initSlideshows();
		
		//fade in on load
		$('.zilla-gallery-container').each(function () {
			var $gallery = $(this),
				imgs = $gallery.find('img'),
				count = imgs.length;

				imgs.load(function() {
				    count--;
				    if (!count) {
				        $gallery.find('.zilla-gallery').addClass('fade-in');
				    }
				}).filter(function() { return this.complete; }).load();				
		});

//=================
//! Comment Form
//=================

		$('.comment-form-comment').on('click', function () {
				$(this).addClass('form-open').next().slideDown(300);
			})
			.prependTo($('.comment-form'))
			.siblings().wrapAll('<div class="comment-form-inner"/>');
		$('.comment-form-inner').hide();

		$('#cancel-comment').on('click', function (e) {
			e.preventDefault();
			$('.comment-form-inner').slideUp(300).prev().removeClass('form-open');
		}).prependTo('.form-submit');

		/* --------------------------------------- */
		/* jPlayer - Audio/Video Media
		/* --------------------------------------- */
		if( $().jPlayer ) {
			var $jplayers = $('.jp-jplayer');

			$jplayers.each(function() {
				var $player = $(this),
					playerType = $player.data('player-type'),
					playerMedia = $player.data('media-info');

				if( playerType === 'video' ) {
					$player.jPlayer({
						ready: function() {
							$(this).jPlayer('setMedia', {
								poster: playerMedia.p,
								m4v: playerMedia.m,
								ogv: playerMedia.o,
							});
						},
						size: {
							width: '100%',
							height: 'auto',
						},
						play: function() { // To avoid multiple jPlayers playing together.
							$(this).jPlayer("pauseOthers");
						},
						swfPath: zillaTHEMENAME.vendorFolder,
						cssSelectorAncestor: playerMedia.ancestor,
						supplied: 'm4v, ogv'
					});

					// Show/Hide player controls when video playing
					$player.bind($.jPlayer.event.playing, function() {
						var gui = $(this).next('.jp-video').find('.jp-interface');
						$(this).add(gui).hover( function() {
							$(gui).stop().animate({ opacity: 1 }, 300);
						}, function() {
							$(gui).stop().animate({ opacity: 0}, 300);
						});
					});

					$player.bind($.jPlayer.event.pause, function() {
						var gui = $(this).next('.jp-video').find('.jp-interface');
						$(this).add(gui).unbind('hover');
						$(gui).stop().animate({ opacity: 1 }, 300);
					});
				} else {
					$player.jPlayer({
						ready: function() {
							$(this).jPlayer('setMedia', {
								poster: playerMedia.p,
								mp3: playerMedia.m,
								oga: playerMedia.o,
							});
						},
						size: {
							width: '100%',
							height: 'auto',
						},
						play: function() { // To avoid multiple jPlayers playing together.
							$(this).jPlayer("pauseOthers");
						},
						preload: 'auto',
						swfPath: zillaTHEMENAME.vendorFolder,
						cssSelectorAncestor: playerMedia.ancestor,
						supplied: 'mp3, ogg'
					});
				}
			});
		} /* jPlayer --- */

//=====================================================
//! Responsive media - FitVids
//=====================================================
		function responsiveVideo() {
			if ( $().fitVids ) {
				$('#content').fitVids();
			}	
		}
		
		responsiveVideo();
		
//=====================================================
//! Misc.
//=====================================================

		//Add some markup for formatted code
		$('pre code').html(function(index, html) {
		    return html.replace(/^(.*)$/mg, "<span class=\"line\">$1</span>");
		});
		
		// Calc the post read time based on the word count
		$('.read-time').each(function () {
			var numWords = $(this).find('.time').data('word-count'),
				wpm = 250,
				readTime = Math.round(numWords/wpm);
				
			if (readTime < 1) {
				readTime = 1;
			}
				
			$(this).find('.time').text(readTime);
		});
		
		// Format the Social Menu
		$('.site-meta .menu li').each(function () {
			var type = $(this).find('a').text();
			
			type = type.toLowerCase();
			
			$(this).find('a').text('').addClass('fa ' + type);
		});
		
		// Move the link above the title in grid view
		$('.page-template-template-home .recent.grid .format-link').each(function () {
			$(this).find('.entry-link').prependTo($(this).find('.entry-header'));
		});
		
		$('.site-logo .retina-img').load(function () {
			var w = $(this).width();
			$(this).css('width', w/2).addClass('loaded');
		});
		
//=====================================================
//! Home
//=====================================================

		//resize the featured images on the top recent posts so the content is bottom aligned
		function sizeHomePosts() {
			$('.featured-post-secondary').each(function () {
				var $post = $(this),
					postHeight = $post.height(),
					textHeight = $post.find('.entry-header').outerHeight(),
					imgHeight = postHeight - textHeight;
					
				$post.find('.entry-thumbnail').height(imgHeight);
			});
		}
		
		sizeHomePosts();
		
		$window.resize(function () {
			sizeHomePosts();
		});
		
		$('.page-template-template-home .post').each(function () {
			$(this).addClass('animate-in');
		});

	});

})(window.jQuery);