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
          <md-switch
            v-model="formData.remember"
            v-text="'Запомнить меня'"
          />
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
import axios from 'axios';

declare interface User {
  email: string,
  password: string,
  remember: boolean,
}

@Component
export default class AuthPage extends Vue {
  formData: User = {
      email: '',
      password: '',
      remember: false,
  };

  private async submitForm() {
    try {
      const data = JSON.stringify(this.formData);
      const response = await axios.post('/auth', data);

      console.log(response);
    } catch (err) {
      console.error(err);
    }
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
</style>
