<template>
  <div class="review-form__input">
    <md-field>
      <md-input
        v-model="selectedValue"
        :disabled="disabled"
        @input="onChangeValue"
      />
    </md-field>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Model, Prop } from 'vue-property-decorator';

@Component
export default class TextInput extends Vue {
  @Model('input') readonly value!: string;

  @Prop({ default: false }) readonly disabled!: boolean;
  @Prop({ default: null }) readonly answerValue!: any;

  selectedValue: string = '';

  onChangeValue() {
    this.$emit('input', this.selectedValue);
  }

  created() {
    this.selectedValue = this.answerValue || '';
  }
}
</script>

<style lang="scss" scoped>

</style>
