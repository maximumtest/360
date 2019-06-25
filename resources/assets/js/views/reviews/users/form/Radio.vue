<template>
  <div class="review-form__radio">
    <md-radio
      v-model="selectedValue"
      v-for="(answer, index) in question.answers"
      :key="index"
      :value="answer"
      :disabled="disabled"
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
  @Prop({ default: false }) readonly disabled!: boolean;
  @Prop({ default: null }) readonly answerValue!: any;

  selectedValue: string = '';

  onChangeValue() {
    this.$emit('change', this.selectedValue);
  }

  created() {
    this.selectedValue = this.answerValue || '';
  }
}
</script>

<style lang="scss" scoped>

</style>
