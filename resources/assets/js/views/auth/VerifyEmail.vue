<template>
  <div class="verify-email-page">
    <update-password
      @submit="submitForm"
    >
      Завершение регистрации
    </update-password>
    <p
      v-if="error"
      v-text="error"
      class="error"
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
export default class VerifyEmailPage extends Vue {
  @Auth.Action verifyEmail!: Function;

  formData: PasswordConfirmation = {
    password: '',
    passwordConfirmation: '',
  };

  error: string | null = null;

  private async submitForm(data: PasswordConfirmation) {
    const updatePasswordRequest: UpdatePasswordRequest = {
      password: data.password,
      password_confirmation: data.passwordConfirmation,
      code: this.$route.params.code,
    };

    const response = await this.verifyEmail(updatePasswordRequest);

    if (response.status > 400) {
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
}
</style>
