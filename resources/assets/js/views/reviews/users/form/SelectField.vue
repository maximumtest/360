<template>
  <div class="review-form__select">
    <md-field>
      <md-select
        v-model="selectedValue"
        :disabled="disabled"
        @md-selected="onChangeValue"
      >
        <md-option
          v-for="(answer, index) in question.answers"
          :key="index"
          :value="answer"
        >
          {{ answer }}
        </md-option>
      </md-select>
    </md-field>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import { Question } from '@/store/questions/types'

@Component
export default class SelectField extends Vue {
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
