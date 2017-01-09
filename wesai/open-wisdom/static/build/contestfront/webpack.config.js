var path = require("path");
var webpack = require("webpack");
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var autoprefixer = require('autoprefixer');
module.exports = {
	entry:{
		main: [
			// 'webpack-dev-server/client?http://localhost:3000', // WebpackDevServer host and port
			// 'webpack/hot/dev-server', // "only" prevents reload on syntax errors
			"./src/components/main.js"
		]
	},
	output: {
		path: path.resolve(__dirname, "build"),
		publicPath: '/build/', // 这个用来成成比如图片的 URL
		filename: "[name].bundle.js"
	},
	module: {
		loaders: [
			{ test: /\.css$/, loader:  ExtractTextPlugin.extract(["css-loader", "postcss-loader"])},
			// { test: /\.css$/, loader: "style-loader!css-loader" },
			// { test: /\.handlebars$/, loader: "handlebars-loader" },
			{ test: /\.handlebars$/, loader: "handlebars-loader?helperDirs[]=" + __dirname + "/src/components/handlebars/helpers" },
			{ test: /\.js$/, include: path.join(__dirname, 'src'), loader: 'babel', exclude:/node_modules/},
			{ test: /\.(gif|jpg|png|woff|svg|eot|ttf)\??.*$/, loader: 'url-loader?limit=50000&name=[path][name].[ext]'}
		]
	},
	plugins: [
		new webpack.ProvidePlugin({
			$: "webpack-zepto",
			Zepto: "webpack-zepto",
			"window.Zepto": "webpack-zepto"
		}),
		// new webpack.HotModuleReplacementPlugin(),
		// new webpack.optimize.CommonsChunkPlugin('common.js'),
		new ExtractTextPlugin("[name].css"),
	],
	postcss: [autoprefixer()]
};

