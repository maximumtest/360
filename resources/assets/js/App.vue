<template>
  <component :is="currentLayout" />
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import DefaultLayout from './layouts/Default.vue';
import AuthLayout from './layouts/Auth.vue';

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
}
</script>
