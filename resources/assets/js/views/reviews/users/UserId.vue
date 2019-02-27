<template>
  <div class="review-user-page">
    <h1>Пользователь {{ $route.params.userId }}</h1>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component
export default class ReviewUserPage extends Vue {};
</script>

<style lang="scss" scoped>
.review-user-page {
  padding: 12px 24px;
}
</style>
