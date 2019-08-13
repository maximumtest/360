<template>
  <div class="signin-page">
    <v-form
      ref="form"
      v-model="valid"
      class="signin-page__form"
      lazy-validation
      @submit.prevent="submitForm"
    >
      <h2 class="signin-page__title">
        Вход
      </h2>

      <p
        v-if="error"
        class="error"
        v-text="error"
      />

      <v-text-field
        id="email"
        v-model="formData.email"
        label="E-mail"
        required
      />

      <v-text-field
        id="password"
        v-model="formData.password"
        label="Password"
        required
      />

      <router-link
        :to="{ name: 'forgotPassword' }"
        class="signin-page__link"
        v-text="'Забыли пароль?'"
      />

      <v-checkbox
        v-model="formData.remember"
        label="Запомнить меня"
      />

      <v-btn type="submit">
        Войти
      </v-btn>
    </v-form>
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
export default class SignInPage extends Vue {
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
}
</script>

<style lang="scss" scoped>
.signin-page {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;

  &__title {
    font-size: 24px;
  }

  &__form {
    width: 500px;
  }

  &__link {
    margin-right: 20px;
  }
}

.error {
  color: #ff5252;
}
</style>
