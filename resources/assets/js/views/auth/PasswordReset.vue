<template>
  <div class="password-reset-page">
    <update-password
      class="password-reset-page__form"
      @submit="submitForm"
    >
      Новый пароль
    </update-password>
    <p
      v-if="message"
      :class="message.status"
      v-text="message.text"
    />
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as authStoreName } from '@/store/auth';
import { UpdatePasswordRequest } from '@/store/auth/types';

import UpdatePassword from '@/components/pages/auth/UpdatePassword.vue';

const Auth = namespace(authStoreName);

interface PasswordConfirmation {
  password: string;
  passwordConfirmation: string;
}

@Component({
  components: {
    updatePassword: UpdatePassword,
  },
})
export default class PasswordResetPage extends Vue {
  @Auth.Action resetPassword!: Function;

  formData: PasswordConfirmation = {
    password: '',
    passwordConfirmation: '',
  };

  message: object | null = null;

  private async submitForm(data: PasswordConfirmation) {
    const updatePasswordRequest: UpdatePasswordRequest = {
      password: data.password,
      password_confirmation: data.passwordConfirmation,
      code: this.$route.params.code,
    };

    const response = await this.resetPassword(updatePasswordRequest);

    if (response.status === 200) {
      this.message = {
        text: 'Пароль успешно изменён. Перейдите на страницу авторизации и авторизуйтесь с новым паролем',
        status: 'info',
      };
    } else {
      this.message = {
        text: 'Ошибка',
        status: 'error',
      };
    }
  }
}
</script>

<style lang="scss" scoped>
.password-reset-page {
  align-items: center;
  display: flex;
  flex-direction: column;
  height: 100vh;
  justify-content: center;

  &__form {
    margin-bottom: 20px;
  }
}
</style>
