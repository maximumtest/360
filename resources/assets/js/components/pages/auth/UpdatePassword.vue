<template>
  <form
    class="md-layout update-password"
    @submit.prevent="$emit('submit', formData)"
  >
    <md-card class="md-layout-item md-size-100 md-small-size-100">
      <md-card-header>
        <div
          class="md-title"
        >
          <slot/>
        </div>
      </md-card-header>
      <md-card-content>
        <md-field class="update-password__form-field">
          <label
            for="password"
            v-text="'Password'"
          />
          <md-input
            v-model="formData.password"
            type="password"
            name="password"
            id="password"
            autocomplete="password"
          />
        </md-field>

        <md-field class="update-password__form-field">
          <label
            for="passwordConfirmation"
            v-text="'Password Confirmation'"
          />
          <md-input
            v-model="formData.passwordConfirmation"
            type="password"
            name="passwordConfirmation"
            id="passwordConfirmation"
            autocomplete="off"
          />
        </md-field>
      </md-card-content>
      <md-card-actions>
        <md-button
          type="submit"
          class="md-raised md-primary"
          v-text="'Complete'"
        />
      </md-card-actions>
    </md-card>
  </form>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

interface PasswordConfirmation {
  password: string;
  passwordConfirmation: string;
}

@Component
export default class UpdatePassword extends Vue {
  formData: PasswordConfirmation = {
    password: '',
    passwordConfirmation: '',
  };
};
</script>

<style lang="scss" scoped>
.update-password {
  width: 500px;
}
</style>
