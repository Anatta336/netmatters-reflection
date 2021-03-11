const path = require('path');
module.exports = {
  entry: {
    app: ['core-js/stable', './src/js/index.js']
  },
  output: {
    filename: 'main.js',
    path: path.resolve(__dirname, 'public/js'),
  },
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ['@babel/preset-env']
          }
        }
      }
    ]
  },
  devtool: "cheap-source-map",
  mode: 'development'
}
