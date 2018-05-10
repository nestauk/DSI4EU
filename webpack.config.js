const webpack = require('webpack');
const path = require('path');
const ExtractTextWebpackPlugin = require('extract-text-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimiseCssAssets = require('optimize-css-assets-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const rimraf = require('rimraf');

let config = {
  entry: './resources/index.js',
  output: {
    path: path.resolve(__dirname, './www'),
    filename: 'main.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/, // files ending with .js
        exclude: /node_modules/, // exclude the node_modules directory
        loader: "babel-loader", // use this (babel-core) loader
        query: {
          presets: ['es2015']
        }
      },
      {
        test: /\.(css|scss)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        use: ExtractTextWebpackPlugin.extract({
          use: ['css-loader?url=false', 'sass-loader'],
          fallback: 'style-loader'
        })
      },
      {
        test: /\.(ttf|eot|svg|gif|png|woff|woff2)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        use: [{
          loader: 'file-loader'
        }]
      }
    ]
  },
  plugins: [
    new ExtractTextWebpackPlugin('assets/scss/style.css'),
    new CopyWebpackPlugin([
      {from: './resources/assets', to: './assets'}
    ], {
      ignore: [
        'scss/*',
      ],
      copyUnmodified: true
    })
  ],
  devServer: {
    contentBase: path.resolve(__dirname, './www'),
    historyApiFallback: true,
    inline: true,
    open: true
  },
  devtool: 'eval-source-map',
  resolve: {
    extensions: ['*', '.js', '.vue', '.json'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js' // 'vue/dist/vue.common.js' for webpack 1
    }
  }
};

if (process.env.NODE_ENV === 'production') {
  rimraf('./www/assets', function () {
    console.log('assets folder removed');
  });

  config.plugins.push(
    new webpack.optimize.UglifyJsPlugin(), // call the uglify plugin
    new OptimiseCssAssets()
  );
}

module.exports = config;