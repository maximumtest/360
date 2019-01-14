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
};
