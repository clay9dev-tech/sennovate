/**
 * TAU(𝛕) is the Mathematical constant representing 2 * pi
 * Since you’re _probably_ multiplying pi by 2 like every time
 * you use it anyway, just use 𝛕 instead!
 * https://tauday.com/tau-manifesto
 *
 * @type {Number}
 */
export const TAU = 6.283185307179586;

/**
 * PHI(φ) is the Mathematical constant representing the golden
 * ratio. _Finally_ a simple handy constant for all those times
 * you need to accurately simulate the spiral arrangement of
 * sunflower seeds!
 *
 * @type {Number}
 */
export const PHI = 1.618033988749894;

/**
 * Multiply degrees by this number to convert to radians
 *
 * @type {Number}
 */
export const DegToRad = 0.017453292519943295;

/**
 * Multiply radians by this number to convert to degrees
 *
 * @type {Number}
 */
export const RadToDeg = 57.29577951308232;

/**
 * Force a number to be no smaller or larger than the specified min and max values
 *
 * @param  {Number} value The value to clamp
 * @param  {Number} min   Minimum allowed value
 * @param  {Number} max   Maximum allowed value
 *
 * @return {Number}       The clamped value
 */
export const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

/**
 * Force a number to be a positive float, with a max value of 1.0
 *
 * @param  {Number} value The value to clamp
 *
 * @return {Number}       The clamped value
 */
export const clamp01 = value => clamp(value, 0, 1);

/**
 * Linearly interpolate a value from a to b using t.
 *
 * Note this is UNCLAMPED, so values of t not between
 * 0 and 1 will return a value not between a and b.
 *
 * @param  {Number} a The "Start" value
 * @param  {Number} b The "End" value
 * @param  {Number} t How far from a->b to go
 *
 * @return {Number}   The interpolated value
 */
export const lerpUnclamped = (a, b, t) => a + t * (b - a);

/**
 * Linearly interpolate a value from a to b using t.
 *
 * @param  {Number} a The "Start" value
 * @param  {Number} b The "End" value
 * @param  {Number} t How far from a->b to go
 *
 * @return {Number}   The interpolated value
 */
export const lerp = (a, b, t) => lerpUnclamped(a, b, clamp01(t));

/**
 * Inverse of lerp(). Use this when you have a value V
 * and you want to know where it is between A and B.
 *
 * @param  {Number} a The "Start" value
 * @param  {Number} b The "End" value
 * @param  {Number} v The value to ilerp
 *
 * @return {Number}   Where v is in relation to a and
 *                    b on the number line, a being 0
 *                    and b being 1
 */
export const inverseLerp = (a, b, v) => (v - a) / (b - a);

/**
 * Returns a positive integer (or zero) that will "circle" around l
 * back to zero. It's similar to modulo, but negative numbers will
 * also work. This is just a generic mathy thing, but it's pretty much
 * made for converting an out-of-bounds array index to a valid one.
 *
 * @example repeatInt(2, 5)  //2
 * @example repeatInt(8, 5)  //3
 * @example repeatInt(8, 4)  //0
 * @example repeatInt(-1, 5) //4
 *
 * @param  {Number} t The value to "unwind"
 * @param  {Number} l The length of the "circle"
 *
 * @return {Number}   Leftover amount
 */
export const repeatInt = (t, l) => {
	t = parseInt(t, 10);
	l = parseInt(l, 10);

	if (!l) {
		return t;
	}

	if (l === 1) {
		return 0;
	}

	while (t >= l) {
		t -= l;
	}

	while (t < 0) {
		t += l;
	}

	return t;
};

/**
 * Similar to the modulo operator, but works with floats
 * and negative numbers. Like repeatInt(), this is good
 * for keeping a number inside some positive bounds
 *
 * @param  {Number} t The value to "unwind"
 * @param  {Number} l The length of the "circle"
 *
 * @return {Number}   Leftover amount
 */
export const repeat = (t, l) => t - l * Math.floor(t / l);
