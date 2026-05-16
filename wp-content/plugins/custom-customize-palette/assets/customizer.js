(function () {
	function onDocumentReady() {
		document.addEventListener('click', onClick);
		document.addEventListener('input', onInput);
	}

	function onInput(e) {
		if (e.target.classList.contains('customize-variable-color-text-input')) {
			e.preventDefault();
			return onColorTextInput(e.target);
		}

		if (e.target.classList.contains('customize-variable-color-input')) {
			e.preventDefault();
			return onColorInput(e.target);
		}
	}

	function onClick(e) {
		if (e.target.classList.contains('customizer-variable-color-reset')) {
			e.preventDefault();
			return onClickReset(e.target);
		}

		if (e.target.classList.contains('customizer-variable-color-sample')) {
			e.preventDefault();
			return onClickColorSample(e.target);
		}
	}

	function onClickReset(resetButton) {
		const inputWrapper = resetButton.closest('.customize-variable-color-input-wrapper');
		const colorInput = inputWrapper.querySelector('[type="text"]');
		const defaultValue = colorInput.dataset.defaultValue;

		colorInput.value = defaultValue;
		colorInput.dispatchEvent(new Event('input', { bubbles: true }));

		return false;
	}

	function onColorTextInput(input) {
		const inputWrapper = input.closest('.customize-variable-color-input-wrapper');
		const colorSample = inputWrapper.querySelector('.customize-variable-color-sample');
		const colorInput = inputWrapper.querySelector('.customize-variable-color-input');

		colorSample.style.backgroundColor = input.value;
		colorInput.value = input.value;

		return false;
	}

	function onColorInput(input) {
		const inputWrapper = input.closest('.customize-variable-color-input-wrapper');
		const colorTextInput = inputWrapper.querySelector('.customize-variable-color-text-input');
		const colorSample = inputWrapper.querySelector('.customize-variable-color-sample');

		colorSample.style.backgroundColor = input.value;
		colorTextInput.value = input.value;
		colorTextInput.dispatchEvent(new Event('input', { bubbles: true }));
	}

	function onClickColorSample(colorSample) {
		const inputWrapper = colorSample.closest('.customize-variable-color-input-wrapper');
		const colorInput = inputWrapper.querySelector('.customize-variable-color-input');

		colorInput.click();
	}

	document.addEventListener('DOMContentLoaded', onDocumentReady);
})();
