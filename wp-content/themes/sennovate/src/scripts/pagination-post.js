let $ = window.jQuery;

function loadPosts(page = 1, search = '') {
  const selectedCategories = [];
  $('.category-checkbox:checked').each(function () {
    selectedCategories.push($(this).val());
  });

  $.ajax({
    url: pagination_post_param.ajax_url,
    type: 'POST',
    data: {
      action: 'pagination_posts',
      post_type: pagination_post_param.post_type,
      page: page,
      cat: selectedCategories, // array of category IDs
      nonce: pagination_post_param.nonce,
      sq: search, // search term
    },
    beforeSend: function () {
      $('.post-content').addClass('loading');
    },
    success: function (res) {
      if (res) {
        $('.post-content').html(res);

        // Mark current pagination link
        $('.pagination-wrapper .page-numbers').removeClass('current');
        $('.pagination-wrapper .page-numbers').each(function () {
          if ($(this).text() == page) {
            $(this).addClass('current');
          }
        });

        // Scroll to results
        var postContent = $('.post-content');
        if (postContent.length) {
          window.scrollTo({
            top: postContent.offset().top - 150,
            behavior: 'smooth'
          });
        }
      }

      $('.post-content').removeClass('loading');
    },
    error: function () {
      alert('Failed to load posts. Try again.');
      $('.post-content').removeClass('loading');
    }
  });
}

function onDocumentReady() {
  // Pagination Click
  $(document).on('click', '.pagination-wrapper .page-numbers', function (e) {
    e.preventDefault();
    const button = $(this);
    const href = button.attr('href');
    const match = href.match(/paged=(\d+)/) || href.match(/page\/(\d+)/);
    const page = match ? parseInt(match[1]) : 1;
    const search = $('#ajax-search-form input[name="sq"]').val() || '';

    // Optional: update URL
    //window.history.pushState({}, '', href);

    loadPosts(page, search);
  });

  // Search Form Submit
  $('#ajax-search-form').on('submit', function (e) {
    e.preventDefault();
    const search = $(this).find('input[name="sq"]').val();
    //window.history.pushState({}, '', '?sq=' + encodeURIComponent(search));
    loadPosts(1, search);
  });

  // Category Checkbox Change
  $(document).on('change', '.category-checkbox', function () {
    const search = $('#ajax-search-form input[name="sq"]').val() || '';
    //window.history.pushState({}, '', window.location.pathname); // reset query
    loadPosts(1, search);
  });

// ✅ Clear Filters Button
  $(document).on('click', '.clear-filter', function () {
    // Clear search input
    $('#ajax-search-form input[name="sq"]').val('');

    // Uncheck all checkboxes
    $('.category-checkbox').prop('checked', false);

    // Optional: reset URL (no query params)
    window.history.pushState({}, '', window.location.pathname);

    // Reload posts without filters
    loadPosts(1, '');
  });
}

// Handle browser back/forward navigation
window.onpopstate = function () {
  const params = new URLSearchParams(window.location.search);
  const search = params.get('sq') || '';
  const page = parseInt(params.get('paged')) || 1;
  loadPosts(page, search);
};

$(onDocumentReady);

