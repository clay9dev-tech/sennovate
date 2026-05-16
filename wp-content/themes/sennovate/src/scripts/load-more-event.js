let $ = window.jQuery;

function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.has(param) ? true : '';
}

function onDocumentReady() {
    let page = 2; // already loaded page 1
    const button = $('#load-more-event');

    button.on('click', function () {
        $.ajax({
            url: load_more_params.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_event',
                page: page,
                posts_per_page: load_more_params.posts_per_page,
                nonce: load_more_params.nonce,
                past_event: getQueryParam('past_event'),
                upcoming_event: getQueryParam('upcoming_event'),
            },
            beforeSend: function () {
                button.text('Loading...');
            },
            success: function (res) {
                if (res.success) {
                    console.log(res.data);
                    $('#event-container').append(res.data);
                    page++;
                    button.text('Load More');
                } else {
                    button.remove();
                }
            },
            error: function () {
                button.text('Error');
            }
        });
    });
}

$(onDocumentReady);

