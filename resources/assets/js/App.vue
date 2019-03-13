<template>
  <component :is="currentLayout"/>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import DefaultLayout from './layouts/Default';
import AuthLayout from './layouts/Auth';

@Component({
  components: {
    default: DefaultLayout,
    auth: AuthLayout,
  },
})
export default class App extends Vue {
  get currentLayout() {
    return this.$route.meta.layout || 'default';
  }
};
</script>

<style lang="scss" scoped>
</style>
