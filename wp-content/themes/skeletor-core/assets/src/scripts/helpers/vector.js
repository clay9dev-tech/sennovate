/**
 * Add two Vector2s
 *
 * @param   {Array[2]} a
 * @param   {Array[2]} b
 *
 * @returns {Array[2]} a + b
 */
export const Add = (a, b) => [a[0] + b[0], a[1] + b[1]];

/**
 * Subtract Vector2 b from Vector2 a
 *
 * @param   {Array[2]} a
 * @param   {Array[2]} b
 *
 * @returns {Array[2]} a - b
 */
export const Subtract = (a, b) => [a[0] - b[0], a[1] - b[1]];

/**
 * Scale Vector2 a by Vector2 b
 *
 * @param   {Array[2]} a
 * @param   {Array[2]} b
 *
 * @returns {Array[2]} a * b
 */
export const Scale = (a, b) => [a[0] * b[0], a[1] * b[1]];

/**
 * Multiply Vector2 a by scalar value b
 *
 * @param   {Array[2]} a
 * @param   {Number} b
 *
 * @returns {Array[2]} a * b
 */
export const Multiply = (V, n) => [V[0] * n, V[1] * n];

/**
 * Return the Dot Product of Vectors a and b.
 * If this number is zero, the two vectors are perpendicular.
 * If this number is > 0, the two vectors are facing in the same direction
 * If this number is < 0, the two vectors are facing in opposite directions
 *
 * @param   {Array[2]} a
 * @param   {Array[2]} b
 *
 * @returns {Number}
 */
export const DotProduct = (a, b) => a[0] * b[0] + a[1] * b[1];

/**
 * Test for Vector2 equality
 *
 * @param   {Array[2]} a
 * @param   {Array[2]} b
 *
 * @returns {Boolean}
 */
export const Equals = (a, b) => a[0] === b[0] && a[1] === b[1];

/**
 * Rotate Vector2 by theta.
 * @param   {Array[2]} V     The vector to rotate
 * @param   {Number}  theta Rotation angle in RADIANS
 *
 * @returns {Array[2]}
 */
export const Rotate = (V, theta) => {
	const sin = Math.sin(theta);
	const cos = Math.cos(theta);

	return [V[0] * cos - V[1] * sin, V[0] * sin + V[1] * cos];
};

/**
 * Returns the "left-hand" perpendicular Vector2 to the supplied Vector2
 * @param   {Array[2]} V
 *
 * @returns {Array[2]}
 */
export const LeftNormal = V => [V[1], -V[0]];

/**
 * Returns the "right-hand" perpendicular Vector2 to the supplied Vector2
 * @param   {Array[2]} V
 *
 * @returns {Array[2]}
 */
export const RightNormal = V => [-V[1], V[0]];

/**
 * Returns the length of the supplied Vector2
 * @param   {Array[2]} V
 *
 * @returns {Number}
 */
export const Magnitude = V => Math.hypot(V[0], V[1]);

/**
 * Returns a Vector2 with the same direction as V, but with a length of 1
 * @param   {Array[2]} V
 *
 * @returns {Array[2]}
 */
export const Normalized = V => Multiply(V, 1 / Magnitude(V));
