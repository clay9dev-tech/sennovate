let $ = window.jQuery;

function onDocumentReady() {
    const headings = document.querySelectorAll('.payroll-software-lists .wp-block-heading');
    const covers = document.querySelectorAll('.payroll-cover-section .wp-block-cover');

    headings.forEach(heading => {
        heading.addEventListener('click', function() {
            const id = this.id;
            const coverId = id + '-cover';

            headings.forEach(h => h.classList.remove('active'));
            this.classList.add('active');

            covers.forEach(cover => {
                if (cover.id === coverId) {
                    cover.style.display = 'block';
                } else {
                    cover.style.display = 'none';
                }
            });
        });
    });
}

$(onDocumentReady);
