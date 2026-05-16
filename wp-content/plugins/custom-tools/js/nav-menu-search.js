/**
 * Custom Search by post id
 */
jQuery(document).ready(function($){
	var searchTimer;

	if ($('.vtl-quick-search').length > 0 ) {
		$('#nav-menu-meta').on('input', '.vtl-quick-search', function(e) {
			e.preventDefault();
			let searchValue = $('.vtl-quick-search').val();
			$('#tabs-panel-custom-search-login .spinner').addClass( 'is-active');
			$('#tabs-panel-custom-search-login li').hide();
			if ( searchTimer ) {
				clearTimeout( searchTimer );
			}

			searchTimer = setTimeout( function() {
				$.ajax({
					type: 'POST',
					url: vtl_search_ajax_object.ajax_url,
					dataType: 'html',
					data: {
						action	 : 'vtl_search_by_id',
						searchNav: searchValue,
					},
					success: function (data) {
						$('#tabs-panel-custom-search-login .spinner').removeClass( 'is-active' );
						$('#tabs-panel-custom-search-login li').show();
						$('#custom-search-login-checklist').html(data);
					}
				});
			}, 1000 );
		});
	}
});
