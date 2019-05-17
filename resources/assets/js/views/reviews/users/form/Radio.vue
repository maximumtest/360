<template>
  <div class="review-form__radio">
    <md-radio
      v-model="selectedValue"
      v-for="(answer, index) in question.answers"
      :key="index"
      :value="answer"
      @change="onChangeValue"
    >
      {{ answer }}
    </md-radio>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import { Question } from '@/store/questions/types'

@Component
export default class Radio extends Vue {
  @Model('change') readonly value!: string;

  @Prop(Object) readonly question!: Question;

  selectedValue: string = '';

  onChangeValue() {
    this.$emit('change', this.selectedValue);
  }
}
</script>

<style lang="scss" scoped>

</style>
