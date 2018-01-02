const webpack = require('webpack');
const path = require('path');

let settings = [{
  name: "app",
  entry: [
    './resources/src/'
  ],
  output: {
    path: path.join(__dirname, '/public/assets/frontend'),
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
              limit: 10000, /* file smaller than 10kB would be transformed into base64 */
              name: "/images/book/[name].[ext]",
              publicPath: "../assets"
            }
          }
        ]
      },
      { 
        test: /\.(eot|svg|ttf|woff|otf|woff2)$/,
        loader: 'url-loader',
        options:{
          limit: 65000,
          mimetype: "application/octet-stream",
          name: "/fonts/[name].[ext]",
          publicPath: "../assets"          
        } 
      }
    ]
  },
  resolve: {
    extensions: ['.js','.sass', ".jsx"]
  },
  devServer: {
    port: process.env.PORT || 8080,
    host: "localhost",
    contentBase: "./resources/views",
    historyApiFallback: {
      index: 'resources/views/book.blade.php'
    },
    hot: true,
    inline: true
  },
  plugins: [
    new webpack.NamedModulesPlugin(),
    new webpack.ProvidePlugin({
      React: 'react',
      ReactDOM:'react-dom',
      ReactBootstrap: 'react-bootstrap',
      axios: 'axios'
    })
  ]
}];

module.exports = settings;