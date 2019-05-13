<template>
  <div class="review-form__textarea">
    <md-field>
      <md-textarea
        v-model="selectedValue"
        @input="onChangeValue"
      />
    </md-field>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Model } from 'vue-property-decorator';

@Component
export default class TextareaField extends Vue {
  @Model('input') readonly value!: string;

  selectedValue: string = '';

  onChangeValue() {
    this.$emit('input', this.selectedValue);
  }
}
</script>

<style lang="scss" scoped>

</style>
