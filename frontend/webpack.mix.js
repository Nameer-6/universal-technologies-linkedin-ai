import { react } from 'laravel-mix';
const NodePolyfillPlugin = require('node-polyfill-webpack-plugin');

react('resources/js/app.jsx', 'public/js')
