<template>
  <div class="verify-email-page">
    <form
      class="md-layout verify-email-page__form"
      @submit.prevent="submitForm"
    >
      <md-card class="md-layout-item md-size-100 md-small-size-100">
        <md-card-header>
          <div
            class="md-title"
            v-text="'Завершение регистрации'"
          />
          <p
            v-if="error"
            v-text="error"
            class="error"
          />
        </md-card-header>
        <md-card-content>
          <md-field class="auth-page__form-field">
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

          <md-field class="auth-page__form-field">
            <label
              for="password_confirmation"
              v-text="'Password Confirmation'"
            />
            <md-input
              v-model="formData.passwordConfirmation"
              type="password"
              name="password_confirmation"
              id="password_confirmation"
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
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as authStoreName } from '@/store/auth';
import { VerifyEmailRequest } from '@/store/auth/types';

const Auth = namespace(authStoreName);

interface PasswordConfirmation {
  password: string;
  passwordConfirmation: string;
}

@Component
export default class VerifyEmail extends Vue {
  @Auth.Action verifyEmail: any;

  formData: PasswordConfirmation = {
    password: '',
    passwordConfirmation: '',
  };

  error: string | null = null;

  private async submitForm() {
    const verifyEmailRequest: VerifyEmailRequest = {
      password: this.formData.password,
      password_confirmation: this.formData.passwordConfirmation,
      code: this.$route.params.code,
    };

    const result = await this.verifyEmail(verifyEmailRequest);

    if (!result) {
      this.error = 'Ошибка';

      return;
    }

    this.$router.push('/');
  }
};
</script>

<style lang="scss" scoped>
  .verify-email-page {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;

    &__form {
      width: 500px;
    }
  }
</style>
