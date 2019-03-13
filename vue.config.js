const path = require('path');

const resolve = {
  alias: {
    '@': path.resolve('resources/assets/js'),
  },
  extensions: ['.ts', '.vue', '.json'],
};

module.exports = {
  configureWebpack: {
    resolve,
  },
  outputDir: 'public/static'
};
