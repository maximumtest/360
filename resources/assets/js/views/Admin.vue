<template>
  <div class="admin-page">
    <md-tabs md-sync-route>
      <md-tab
        v-for="tab in tabs"
        :key="tab.label"
        :md-label="tab.label"
        :to="tab.to"
      ></md-tab>
    </md-tabs>

    <router-view/>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import 'jsgrid/dist/jsgrid.min.css';
import 'jsgrid/dist/jsgrid-theme.min.css';
import 'jsgrid/external/jquery/jquery-1.8.3';
import 'jsgrid/dist/jsgrid.min';

@Component
export default class AdminPage extends Vue {
  data() {
    return {
      tabs: [
        {
          to: '/admin/users',
          label: 'Пользователи',
        },
      ],
    };
  }
}
</script>

<style lang="scss">
  .js-grid {
    color: #333;
    overflow-y: hidden;
  }

  .jsgrid-grid-header,
  .jsgrid-grid-body {
    overflow-y: hidden;
  }
</style>
