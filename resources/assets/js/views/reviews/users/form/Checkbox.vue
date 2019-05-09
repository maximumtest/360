<template>
  <div class="review-form__checkbox">
    <md-checkbox
      v-model="selectedValue"
      v-for="(answer, index) in question.answers"
      :key="index"
      :value="answer"
      @change="onChangeValue"
    >
      {{ answer }}
    </md-checkbox>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import { Question } from '@/store/questions/types'

@Component
export default class Checkbox extends Vue {
  @Model('change') readonly value!: string;

  @Prop(Object) readonly question!: Question;

  selectedValue: Array<string> = [];

  onChangeValue() {
    this.$emit('change', this.selectedValue);
  }
}
</script>

<style lang="scss" scoped>

</style>
