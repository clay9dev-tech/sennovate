let $ = window.jQuery;
function onDocumentReady() {
	let body = $(document.body);
	if (body.hasClass('single-post') && body.hasClass('post-type-post')) {
        blogContentHeadingAddId();

	}
}

function blogContentHeadingAddId(){
	var sidebarlist = $(".blog-sidebar-widgets .sidebar-menu-item");
    $('.blog-content-wrapper h2').each(function(){
		let $_this = $(this);
		sidebarlist.each(function(idx, li) {
			if($(li).find('a').text() === $_this.text()) {
				let slug = $(li).find('a').attr('href');
				slug = slug.replace('#', '');
				$_this.attr("id", slug);
			}
		});
        $_this.attr("class",'anchor-content-block');
    })
}

$(onDocumentReady);


jQuery(function ($) {
  const links = $('.anchor-menu-link[href^="#"]:not([href="#n-a"])');

  const sections = links.map(function () {
    const target = $($(this).attr('href'));
    return target.length ? target : null;
  });

  function onScroll() {
    const scrollPos = $(window).scrollTop() + 250;
    let currentId = null;

    sections.each(function () {
      if ($(this).offset().top <= scrollPos) {
        currentId = this.attr('id');
      }
    });

    links.removeClass('active');
    if (currentId) {
      links.filter(`[href="#${currentId}"]`).addClass('active');
    }
  }

  // Scroll listener
  $(window).on('scroll', onScroll);
  onScroll();

  // Click handler
  links.on('click', function (e) {
    e.preventDefault();

    // Remove active from all, add to clicked
    links.removeClass('active');
    $(this).addClass('active');

    const target = $($(this).attr('href'));
    if (target.length) {
      $('html, body').animate({
        scrollTop: target.offset().top - 150 // adjust for header height
      }, 500);
    }
  });
});

