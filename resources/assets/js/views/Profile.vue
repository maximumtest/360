<template>
  <div class="profile-page">
    <md-avatar class="profile-page__avatar md-avatar-icon md-large md-accent">
      <img
        :src="user.avatar || null"
        :alt="avatarPlaceholder"
        class="profile-page__avatar-img"
      >

      <div
        class="profile-page__avatar-popover-change"
        @click="changeAvatar"
      >
        Изменить
      </div>
    </md-avatar>

    <div>
      <md-button
        class="md-accent profile-page__drop-avatar-btn"
        @click="dropAvatar"
      >
        Drop Avatar
      </md-button>
    </div>

    <p v-if="user.name">Имя: {{ user.name }}</p>
    <p>Почта: {{ user.email }}</p>

    <form @submit.prevent="handleForm">
      <input
        type="file"
        name="avatar"
        class="profile-page__avatar-file"
        @change="handleAvatarChange"
      >

      <md-field class="md-size-20 md-layout-item">
        <label>Новый пароль</label>
        <md-input v-model="password" name="password" type="password"></md-input>
      </md-field>

      <input type="hidden" name="removeAvatar" v-model="removeAvatar">

      <md-button
        class="md-raised md-primary profile-page__submit-btn"
        type="submit"
      >
        Save
      </md-button>
    </form>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as authStoreName } from '@/store/auth';
import { name as usersStoreName } from '@/store/users';
import { User } from '@/store/auth/types';

const Auth = namespace(authStoreName);
const Users = namespace(usersStoreName);

@Component
export default class UsersPage extends Vue {
  @Auth.State user!: User;
  @Users.Action updateProfile!: (request: FormData) => Response;

  avatarField: HTMLInputElement | null = null;
  avatarImg: HTMLImageElement | null = null;
  password: string = '';
  removeAvatar: number = 0;

  get avatarPlaceholder() {
    const { name, email } = this.user;

    return (name || email).charAt(0);
  }

  changeAvatar() {
    this.avatarField!.click();
  }

  refreshAvatar(avatarFile: File) {
    const reader = new FileReader();
    reader.onloadend = () => this.avatarImg!.src = <string>reader.result;
    reader.readAsDataURL(avatarFile);
    this.removeAvatar = 0;
  }

  dropAvatar() {
    this.avatarField!.value = '';
    this.avatarImg!.src = '';
    this.removeAvatar = 1;
  }

  async handleForm(event: Event) {
    const formData = new FormData(<HTMLFormElement>event.target);

    await this.updateProfile(formData);
  }

  handleAvatarChange(event: Event) {
    const avatarFile: File = (<HTMLInputElement>event.target).files![0];

    this.refreshAvatar(avatarFile);
  }

  mounted() {
    this.avatarField = document.querySelector('.profile-page__avatar-file');
    this.avatarImg = document.querySelector('.profile-page__avatar-img');
  }
}
</script>

<style lang="scss" scoped>
  .profile-page {
    padding: 20px;

    &__drop-avatar-btn,
    &__submit-btn {
      margin: 10px 0;
    }

    &__avatar {
      min-width: 100px;
      min-height: 100px;

      &-file {
        display: none;
      }

      &-img {
        position: relative;

        &:after {
          display: flex;
          position: absolute;
          justify-content: center;
          align-items: center;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          content: attr(alt);
          background: #ff5252;
          color: #fff;
        }
      }

      &-popover-change {
        position: absolute;
        left: 0;
        bottom: 0;
        height: 0;
        width: 100%;
        background: #000;
        transition: 0.3s ease;
        opacity: 0;
        z-index: 10;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 17px;
        cursor: pointer;
      }

      &:hover {
        .profile-page__avatar-popover-change {
          opacity: .8;
          height: 50%;
        }
      }
    }
  }
</style>
