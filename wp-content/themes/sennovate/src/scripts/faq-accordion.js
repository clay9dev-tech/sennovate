function onDocumentReady() {
	if (!document.getElementsByClassName('faq-accordion').length) {
		return;
	}
	let faq = '';
	let faqHeadings = '';
	let faqDescription = '';
	faq = document.querySelector('.faq-accordion');
	faqHeadings = faq.querySelectorAll('.accordion-heading');
	faqDescription = faq.querySelectorAll('.accordion-description');

	if ((faqHeadings.length > 0) && (faqDescription.length > 0)) {
		//faqHeadings[0].parentNode.classList.add('active');
		//faqDescription[0].setAttribute('style','height:' + faqDescription[0].scrollHeight + 'px');
	}

	faqHeadings.forEach(faqHeading => {
		faqHeading.addEventListener('click', function () {

			if (!this.parentNode.classList.contains('active')) {
				faqHeadings.forEach((el) => {
					el.parentNode.classList.remove('active');
				});

				faqDescription.forEach((el) => {
					el.setAttribute('style','height:0');
				});
			}

			if (this.parentNode.classList.contains('active')) {
				this.parentNode.classList.remove('active');
				this.nextElementSibling.setAttribute('style','height:0');
				return;
			}
			this.parentNode.classList.add('active');
			this.nextElementSibling.setAttribute('style','height:' + this.nextElementSibling.scrollHeight + 'px');
		});
	});
}

document.addEventListener('DOMContentLoaded', onDocumentReady);