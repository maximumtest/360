<template>
  <div class="auth-page">
    <transition
      name="router-anim"
      mode="out-in"
    >
      <router-view/>
    </transition>
  </div>
</template>

<style lang="scss" scoped>
.auth-page {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  &__form {
    width: 500px;
  }
}
</style>
