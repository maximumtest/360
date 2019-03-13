<template>
  <div class="main-page">
    <h2 class="md-title main-page__title">Главная страница</h2>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component
export default class Main extends Vue {};
</script>

<style lang="scss" scoped>
.main-page {
  padding: 20px;
}
</style>
