<template>
  <div class="auth-page">
    <form
      class="md-layout auth-page__form"
      @submit.prevent="submitForm"
    >
      <md-card class="md-layout-item md-size-100 md-small-size-100">
        <md-card-header>
          <div
            class="md-title"
            v-text="'Вход'"
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
              for="email"
              v-text="'Email'"
            />
            <md-input
              v-model="formData.email"
              type="email"
              name="email"
              id="email"
              autocomplete="email"
            />
          </md-field>
        </md-card-content>
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
        </md-card-content>
        <md-card-actions>
          <md-checkbox v-model="formData.remember" class="md-primary">Запомнить меня</md-checkbox>
          <md-button
            type="submit"
            class="md-raised md-primary"
            v-text="'Войти'"
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
import { LoginRequest } from '@/store/auth/types';

const Auth = namespace(authStoreName);

interface User {
  email: string,
  password: string,
  remember: boolean,
}

@Component
export default class AuthPage extends Vue {
  @Auth.Action login!: Function;

  formData: User = {
    email: '',
    password: '',
    remember: false,
  };

  error: string | null = null;

  private async submitForm() {
    const loginRequest: LoginRequest = {
      email: this.formData.email,
      password: this.formData.password,
    };

    const response = await this.login(loginRequest);

    if (response.status > 400) {
      this.error = 'Ошибка входа';

      return;
    }

    this.$router.push('/');
  }
};
</script>

<style lang="scss" scoped>
  .auth-page {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    &__form {
      width: 500px;
    }
  }

  .error {
    color: #ff5252;
  }
</style>
