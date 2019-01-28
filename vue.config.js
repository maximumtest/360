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
  publicPath: undefined,
  outputDir: './public',
  assetsDir: undefined,
  runtimeCompiler: undefined,
  productionSourceMap: undefined,
  parallel: undefined,
  css: undefined
};
