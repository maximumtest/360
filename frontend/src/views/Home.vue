<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png">
    <RoundTable :msg="title"/>
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
