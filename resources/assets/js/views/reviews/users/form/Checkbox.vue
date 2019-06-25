<template>
  <div class="review-form__checkbox">
    <md-checkbox
      v-model="selectedValue"
      v-for="(answer, index) in question.answers"
      :key="index"
      :value="answer"
      :disabled="disabled"
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
  @Prop({ default: false }) readonly disabled!: boolean;
  @Prop({ default: null }) readonly answerValue!: Array<any>;

  selectedValue: Array<string> = [];

  onChangeValue() {
    this.$emit('change', this.selectedValue);
  }

  created() {
    this.selectedValue = this.answerValue || [];
  }
}
</script>

<style lang="scss" scoped>

</style>
