import $ from 'jquery';

function onDocumentReady() {
	if ($('.video-modal').length > 0) {
		$('.video-modal a').modaal({
			type: 'iframe',
			iframe: {
				markup: '<div class="mfp-iframe-scaler">'+
						  '<div class="mfp-close"></div>'+
						  '<iframe class="mfp-iframe" frameborder="0" allowfullscreen allow="autoplay"></iframe>'+
						'</div>',
				patterns: {
					youtube: {
						index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
						id: 'v=',
						src: '//www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe.
					},
					vimeo: {
						index: 'vimeo.com/',
						id: '/',
						src: '//player.vimeo.com/video/%id%?autoplay=1&mute=0'
					}
				},
				srcAction: 'iframe_src',
			  }
		});
	}

	if ($('.video-popup-modal').length > 0) {
		$('.video-popup-modal a').modaal({
			type: 'iframe',
			iframe: {
				markup: '<div class="mfp-iframe-scaler">'+
						  '<div class="mfp-close"></div>'+
						  '<iframe class="mfp-iframe" frameborder="0" allowfullscreen allow="autoplay"></iframe>'+
						'</div>',
				patterns: {
					youtube: {
						index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
						id: 'v=',
						src: '//www.youtube.com/embed/%id%?autoplay=1&mute=0' // URL that will be set as a source for iframe.
					},
					vimeo: {
						index: 'vimeo.com/',
						id: '/',
						src: '//player.vimeo.com/video/%id%?autoplay=1&mute=0'
					}
				},
				srcAction: 'iframe_src',
			  }
		});
	}

	$('.signup-btn').modaal({
		content_source: '#SignupModal',
		after_open: function() {
			$('.modaal-container').addClass('signup-modaal-container');
		}
	});

}

document.addEventListener('DOMContentLoaded', onDocumentReady);