import jQuery from 'jquery';

/**
 * Asynchronously loads JavaScript
 * @param  {string}  url  JavaScript URL
 */
export function loadJS(url) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = url;
    s.async = true;

    document.head.appendChild(s);
}

/**
 * Asynchronously loads CSS
 * @param  {string}  url  Stylesheet URL
 */
export function loadCss(url) {
    var ss = document.styleSheets;

    for (var i = 0, max = ss.length; i < max; i++) {
        if (ss[i].href === url) {
            return;
        }
    }

    var l = document.createElement('link');
    l.type = 'text/css';
    l.rel = 'stylesheet';
    l.href = url;
    document.head.appendChild(l);
}

/**
 * Throttles taxing functions
 * https://davidwalsh.name/javascript-debounce-function
 *
 * @param  {function}  func       Function you want to run
 * @param  {integer}   wait       Milliseconds to wait before calling function
 * @param  {boolean}   immediate  If true, trigger function on the leading edge, instead of the trailing
 */
export function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

/**
 * Checks if an element in the viewport
 * @param  {object}  el Element
 * @param  {boolean}  allowpartial Returns true as soon as the element is (at least partially) in view; otherwise full element must be inside viewport
 * @return {boolean}    The element is/isn't in the viewport
 */
export function isElementInViewport(el, allowpartial) {

    // If a jQuery object has been passed
    if (typeof jQuery === 'function' && el instanceof jQuery) {
        el = el[0];
    }

    var rect = el.getBoundingClientRect();

	if (allowpartial) {
		return (
			rect.top < window.innerHeight && rect.bottom >= 0
		);
	} else {
		return (
			rect.top >= 0 &&
			rect.left >= 0 &&
			rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
			rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
		);
	}
}

/**
 * calculates the element's height using margin and offsetHeight of the children;
 *
 * @param {DOMObject}} el
 * @returns {string} a pixel declaration of the inner height
 */
 export function getElementHeight(el) {
	let h = 0;
	for(let i = 0; i < el.children.length; i++) {
		h += el.children[i].offsetHeight;
		const style = getComputedStyle(el.children[i]);
		h += (parseInt(style.marginTop) || 0);
		h += (parseInt(style.marginBottom) || 0);
	}
	return h ? h + "px" : h;
}
