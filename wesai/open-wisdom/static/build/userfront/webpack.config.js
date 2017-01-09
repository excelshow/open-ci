var path = require("path");
var webpack = require("webpack");
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var autoprefixer = require('autoprefixer');
module.exports = {
  entry:{
    main: ["./app.js"]
  },
  output: {
      path: path.resolve(__dirname, "build"),
      // publicPath: '/build/', // 这个用来成成比如图片的 URL
      filename: "[name].bundle.js"
  },
  module: {
    loaders: [
      // { test: /\.json$/, loader: 'json'},
      { test: /\.css$/, loader:  ExtractTextPlugin.extract(["css-loader", "postcss-loader"])},
      { test: /\.handlebars$/, loader: "handlebars-loader" },
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
      new ExtractTextPlugin("[name].bundle.css"),
  ],
  postcss: [autoprefixer()]
};
