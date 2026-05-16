const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require('path');

const app = resolve(`${process.cwd()}`);
const src = `${app}/assets/src`;
const build = `${app}/assets/dist`;

const config = {
	...defaultConfig,
	target: 'web',
	resolve: {
		modules: ['node_modules', src],
	},
	entry: {
		main: [`${src}/scripts/main.js`, `${src}/styles/main.scss`],
		admin: [`${src}/scripts/admin.js`, `${src}/styles/admin.scss`],
	},
	output: {
		...defaultConfig.output,
		path: build,
	},
	plugins: [
		...defaultConfig.plugins,
		new (require('stylelint-webpack-plugin'))({
			customSyntax: 'postcss-scss',
			files: `${src}/styles`,
			fix: true,
		}),
	],
};

module.exports = config;
