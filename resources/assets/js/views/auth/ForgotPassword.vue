<template>
  <div class="forgot-password-page">
    <form
      class="md-layout forgot-password-page__form"
      @submit.prevent="submitForm"
    >
      <md-card class="md-layout-item md-size-100 md-small-size-100">
        <md-card-header>
          <div
            class="md-title"
            v-text="'Восстановление пароля'"
          />
          <p
            v-if="message"
            v-text="message.text"
            :class="message.status"
          />
        </md-card-header>
        <md-card-content>
          <md-field class="forgot-password-page__form-field">
            <label
              for="email"
              v-text="'Email'"
            />
            <md-input
              v-model="email"
              type="email"
              name="email"
              id="email"
              autocomplete="email"
            />
          </md-field>
        </md-card-content>
        <md-card-actions>
          <md-button
            type="submit"
            class="md-raised md-primary"
          >
            Восстановить пароль
          </md-button>
        </md-card-actions>
      </md-card>
    </form>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as authStoreName } from '@/store/auth';
import { ForgotPasswordRequest } from '@/store/auth/types';

const Auth = namespace(authStoreName);

interface User {
  email: string,
  password: string,
  remember: boolean,
}

@Component
export default class ForgotPasswordPage extends Vue {
  @Auth.Action forgotPassword!: Function;

  email: string = '';

  message: object | null = null;

  private async submitForm() {
    const request: ForgotPasswordRequest = {
      email: this.email,
    };

    const response = await this.forgotPassword(request);

    if (response.status === 200) {
      this.message = {
        text: 'Проверьте почту',
        status: 'info',
      };
    } else {
      this.message = {
        text: 'Ошибка',
        status: 'error',
      };
    }
  }
};
</script>

<style lang="scss" scoped>
.forgot-password-page {
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

.info {
  color: greenyellow;
}
</style>
