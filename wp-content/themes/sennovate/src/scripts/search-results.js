let $ = window.jQuery;

function onDocumentReady() {
    $('.search-btn').on('click', function () {
        $('.search-body').toggleClass('active');
    });

    $('.search-body-close').on('click', function () {
        $('.search-body').removeClass('active');
    });
}

$(onDocumentReady);
