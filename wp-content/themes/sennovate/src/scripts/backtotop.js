let $ = window.jQuery;

function onDocumentReady() {
    var $backToTop = $(".back-to-top").hide();

    $(window).on('scroll', function() {
        $backToTop.toggle($(this).scrollTop() > 150);
    });

    $backToTop.on('click', function() {
        $("html, body").animate({ scrollTop: 0 }, 500);
    });
}

$(onDocumentReady);
