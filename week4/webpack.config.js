const path = require('path');
const webpack = require('webpack')
const HtmlWebpackPlugin = require('html-webpack-plugin')


module.exports = {

    context: path.resolve(__dirname, 'src'),
    entry: './index.js',

    output: {
        filename: 'index_bundle.js',
        path: path.resolve(__dirname, 'dist'),
    },

    devServer: {
        contentBase: path.join(__dirname, 'dist'),
        compress: true,
        port: 9000
    },

    module: {
        rules: [
            {
                test: /\.css$/i,
                use: ['style-loader', 'css-loader'],
            },
        ],
    },

    plugins: [
        new HtmlWebpackPlugin({
            template: './index.html'
        })
    ]
};