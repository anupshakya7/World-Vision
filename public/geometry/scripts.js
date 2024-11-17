(function ($) {
	function changeText(element, text) {
		if (element.length) {
			element.text(text);
		}
	}

	function toggleDisabled(element) {
		if (element.length) {
			if (!element.attr('disabled')) {
				element.attr('disabled', 'true');
			} else {
				element.removeAttr('disabled');
			}
		}
	}

	function loadMoreEvents() {
		let buttonLoadMore = $('.load-more-events');
		let nextPage = 2;
		buttonLoadMore.click(function (e) {
			e.preventDefault();
			changeText($(this), 'Loading...');
			$(this).toggleClass('disabled');
			let pages = parseInt($(this).data('pages'));
			let layout = $(this).data('acf_fc_layout');
			let target = $(this).data('target');
			let eventType = $(this).data('event_type');
			let placeholder = $('#' + target);

			jQuery.post(website.ajaxURL, {
				'action': 'load_more',
				'pages': pages,
				'next_page': nextPage,
				'event_type': eventType,
				'acf_fc_layout': layout
			}, function(data, textStatus, jqXHR) {
				buttonLoadMore.removeClass('disabled');
				changeText(buttonLoadMore, 'LOAD MORE');
				if (200 == jqXHR.status && data) {
					if (nextPage >= pages) {
						buttonLoadMore.hide();
					}

					// Current page increment
					nextPage = nextPage + 1;
					placeholder.append(data);
					return true;
				}
			});
		});
	}

	function loadMoreNews() {
		let buttonLoadMore = $('.load-more-news');
		let nextPage = 2;
		buttonLoadMore.click(function (e) {
			e.preventDefault();
			changeText($(this), 'Loading...');
			$(this).toggleClass('disabled');
			let pages = parseInt($(this).data('pages'));
			let layout = $(this).data('acf_fc_layout');
			let target = $(this).data('target');
			let newsType = $(this).data('news_type');
			let placeholder = $('#' + target);

			jQuery.post(website.ajaxURL, {
				'action': 'load_more_news',
				'pages': pages,
				'next_page': nextPage,
				'news_type': newsType,
				'acf_fc_layout': layout
			}, function(data, textStatus, jqXHR) {
				buttonLoadMore.removeClass('disabled');
				changeText(buttonLoadMore, 'LOAD MORE');
				if (200 == jqXHR.status && data) {
					if (nextPage >= pages) {
						buttonLoadMore.hide();
					}

					// Current page increment
					nextPage = nextPage + 1;
					placeholder.append(data);
					return true;
				}
			});
		});
	}

	function loadMoreResources() {
		let buttonLoadMore = $('.load-more-resources');
		let nextPage = 2;
		buttonLoadMore.click(function (e) {
			e.preventDefault();
			changeText($(this), 'Loading...');
			$(this).toggleClass('disabled');
			let pages = parseInt($(this).data('pages'));
			let layout = $(this).data('acf_fc_layout');
			let target = $(this).data('target');
			let resourceType = $(this).data('resource_type');
			let placeholder = $('#' + target);

			jQuery.post(website.ajaxURL, {
				'action': 'load_more_resources',
				'pages': pages,
				'next_page': nextPage,
				'resource_type': resourceType,
				'acf_fc_layout': layout
			}, function(data, textStatus, jqXHR) {
				buttonLoadMore.removeClass('disabled');
				changeText(buttonLoadMore, 'LOAD MORE');
				if (200 == jqXHR.status && data) {
					if (nextPage >= pages) {
						buttonLoadMore.hide();
					}

					// Current page increment
					nextPage = nextPage + 1;
					placeholder.append(data);
					return true;
				}
			});
		});
	}

	// Show subscription popup box
	function showSubscriptionPopupBox() {
		let popupX = parseInt(Cookies.get('popupX'));
		if (1 !== popupX) {
			if (window.scrollY >= $(document).height() * 0.5) {
				let subscriptionButton = $('.header-top-subscribe a');
				if (subscriptionButton.length) {
					subscriptionButton.trigger('click');
				}
			}
		}
	}

	function handlePopupAppearance() {
		$(document).on('afterClose.fb', function(e, instance, slide) {
			if (
				jQuery(instance.$trigger).hasClass('btn-subscribe')
				&& typeof Cookies !== 'undefined'
			) {

				// 30 days
				Cookies.set('popupX', '1', { expires: 30 });
			}
		});

		window.addEventListener('scroll', function (e) {
			if (typeof VOH_THEME !== 'undefined' && '1' == VOH_THEME.enable_auto_popup) {
				showSubscriptionPopupBox();
			}
		});
	}

	// Run.
	handlePopupAppearance();
	loadMoreEvents();
	loadMoreNews();
	loadMoreResources();
})(jQuery);
