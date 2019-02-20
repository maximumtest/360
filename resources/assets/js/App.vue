<template>
  <div id="app">
    <div id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>
    </div>
    <router-view/>
  </div>
</template>

<style lang="scss">
// Подключение иконочного шрифта для materialize vue
$material-design-icons-font-path: '~material-design-icons-iconfont/dist/fonts/';
@import '~material-design-icons-iconfont/src/material-design-icons';
</style>
