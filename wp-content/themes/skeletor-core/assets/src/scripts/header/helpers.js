/**
 * Returns an object describing an rgb(a) color.
 *
 * @param {string} color An rgb or rgba color string
 * @returns An object with each channel as a property
 */
export function destructureColor(color) {
	const pattern = /rgba?\((\d{1,3}), ?(\d{1,3}), ?(\d{1,3}),? ?(\d{1,3})?\)?/;
	const [, r, g, b, a] = color.match(pattern);

	return { r, g, b, a };
}

/**
 * Get the luminance level of an rgb(a) color
 *
 * @param {string} color An rgb(a) color to destructure
 * @returns number Color luminosity from 0-255
 */
export function colorToLuminance(color) {
	const { r, g, b } = destructureColor(color);

	return 0.299 * r + 0.587 * g + 0.114 * b;
}

/**
 * Get the opacity level of an rgb(a) color
 *
 * @param {string} color The rgb(a) color
 * @returns The alpha channel value (or 1 if there is no alpha channel)
 */
export function getColorAlpha(color) {
	const { a } = destructureColor(color);

	if (typeof a === 'undefined') {
		return 1;
	}

	return a;
}

/**
 * Returns true if the color luminance is below 128 (accounting for alpha)
 *
 * @param {string} color An rgb(a) color to check
 * @returns boolean
 */
export function isDarkColor(color) {
	const alpha = getColorAlpha(color);
	const lum = colorToLuminance(color);
	const darkness = alpha * (255 - lum);

	return darkness >= 128;
}
