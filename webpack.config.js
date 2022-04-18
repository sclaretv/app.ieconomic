const CopyPlugin = require("copy-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const HtmlWebpackPlugin = require('html-webpack-plugin');

const path = require('path');

module.exports = {
    mode: 'production',
    devtool: 'source-map',
    entry: './src/index.js',
    output: {
        filename: '[name].[contenthash].js',
        path: path.resolve(__dirname, 'dist'),
        assetModuleFilename: '[hash][ext][query]',
        clean: true,
    },
    plugins: [
        new CopyPlugin({
            patterns: [
              { from: "./src/config", to: "config" },
              { from: "./src/controllers", to: "controllers" },
              { from: "./src/models", to: "models" },
              { from: "./src/autoload.php", to: "autoload.php" },
            ],
        }),
        new MiniCssExtractPlugin({
            filename: '[name].[contenthash].css',
        }),
        new HtmlWebpackPlugin({
            title: 'Indicadores económicos',
            template: './src/views/index.html',
            favicon: './src/assets/img/favicon.svg',
        }),
    ],
    module: {
      rules: [
          {
              test: /\.css$/i,
              use: [MiniCssExtractPlugin.loader, 'css-loader'],
          },
          {
              test: /\.(png|svg|jpg|jpeg|gif)$/i,
              type: 'asset/resource',
          },
      ],
    },
}; 