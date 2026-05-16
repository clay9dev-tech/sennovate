const preset = require('postcss-preset-env');
const pxtorem = require('postcss-pxtorem');
const inlineSVG = require('postcss-inline-svg');
const sortMediaQueries = require('postcss-sort-media-queries');
const uniqueSelectors = require('postcss-unique-selectors');
const IconfontWebpackPlugin = require('iconfont-webpack5-plugin');

const pxToRemOptions = {
	propWhiteList: [
		'font',
		'font-size',
		'line-height',
		'letter-spacing',
		'margin',
		'margin-top',
		'margin-right',
		'margin-bottom',
		'margin-left',
		'padding',
		'padding-top',
		'padding-right',
		'padding-bottom',
		'padding-left',
	],
};

const inlineSVGOptions = {
	removeFill: true,
	removeStroke: true,
};

module.exports = (context) => {
	return {
		plugins: [
			new IconfontWebpackPlugin(context.webpackLoaderContext),
			preset(),
			pxtorem(pxToRemOptions),
			inlineSVG(inlineSVGOptions),
			sortMediaQueries(),
			uniqueSelectors(),
		],
	};
};
