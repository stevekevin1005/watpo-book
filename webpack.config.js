const webpack = require('webpack');
const path = require('path');
const autoprefixer = require("autoprefixer");

let settings = [{
  name: "app",
  entry: [
    './resources/src/'
  ],
  output: {
    path: path.join(__dirname, '/public'),
    filename: 'bundle.js'
  },
  module: {
    rules: [
      { test: /\.js?$/, 
        use: ["babel-loader"], 
        exclude: /node_modules/,
        },
      { test: /\.sass$/, 
        use: [
          'style-loader',
          {loader:'css-loader',
            options:{
              minimize: true
            }
          },
          'postcss-loader',
          'sass-loader'
        ], 
        exclude: /node_modules/ 
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 10000 /* file smaller than 10kB would be transformed into base64 */
            }
          }
        ]
      }
    ]
  },
  resolve: {
    extensions: ['.js','.sass', ".jsx"]
  },
  devServer: {
    port: process.env.PORT || 8080,
    host: "localhost",
    contentBase: "./public",
    historyApiFallback: true,
    hot: true,
    inline: true
  },
  plugins: [
    new webpack.NamedModulesPlugin(),
    new webpack.ProvidePlugin({
      React: 'react',
      ReactDOM:'react-dom'
    })
  ]
}];

module.exports = settings;