const path = require('path');

const resolve = {
  alias: {
    '@': path.resolve('src'),
  },
  extensions: ['.ts', '.vue', '.json'],
};

module.exports = {
  configureWebpack: {
    resolve,
  },
  outputDir: '../public',
  indexPath: process.env.NODE_ENV === 'production'
    ? '../resources/views/index.blade.php'
    : 'index.html'
};
