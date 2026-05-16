function onClick(e) {
	var inputWrapper = e.target.closest('.focal-point-input-wrapper');
	if (!inputWrapper) {
		return;
	}

	var pin = inputWrapper.querySelector('.focal-point-pin');
	if (!pin) {
		return;
	}

	var hiddenInput = inputWrapper.querySelector('[name="focal_point"]');
	if (!hiddenInput) {
		return;
	}

	var imageWidth = e.target.offsetWidth;
	var imageHeight = e.target.offsetHeight;
	var x = parseFloat(e.offsetX) / imageWidth;
	var y = parseFloat(e.offsetY) / imageHeight;
	var left = (x * 100) + '%';
	var top = (y * 100) + '%';

	pin.style.left = left;
	pin.style.top = top;

	hiddenInput.value = left + ',' + top;

	window.jQuery(hiddenInput).trigger('change');

	e.preventDefault();
	return false;
}

document.addEventListener('click', onClick);
