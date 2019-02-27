<template>
  <div class="home">
    <h1>Главная</h1>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import RoundTable from '@/components/RoundTable.vue'; // @ is an alias to /src

@Component({
  components: {
    RoundTable,
  },
})
export default class Home extends Vue {
  private number = 360 as number;

  get title(): string {
    return `Welcome to ${this.number} review project` as string;
  }
}
</script>
