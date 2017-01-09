var path = require("path");
var webpack = require("webpack");

module.exports = {
  entry:{
    contest: ["./src/components/contest/main.js"],
    venue: ["./src/components/venue/venue.js"]
  },
  output: {
      path: path.resolve(__dirname, "build"),
      publicPath: '/build/', // 这个用来成成比如图片的 URL
      filename: "[name].bundle.js"
  },
  module: {
    loaders: [
      { test: /\.handlebars$/, loader: "handlebars-loader" },
      { test: /\.css$/, loader: "style-loader!css-loader" },
      { test: /\.js$/, include: path.join(__dirname, 'src'), loader: 'babel', exclude:/node_modules/},
      { test: /\.(gif|jpg|png|woff|svg|eot|ttf)\??.*$/, loader: 'url-loader?limit=50000&name=[path][name].[ext]'}
    ]
  },
  plugins: [
      new webpack.ProvidePlugin({
          $: "webpack-zepto",
          Zepto: "webpack-zepto",
          "window.Zepto": "webpack-zepto"
      })
  ],
  
};
