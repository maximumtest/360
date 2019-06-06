<template>
  <div
    v-if="user"
    class="user-profile-page"
  >
    <md-avatar class="md-avatar-icon md-large md-accent">
      <img
        v-if="user.avatar"
        :src="user.avatar"
      >
      <md-icon v-else>face</md-icon>
    </md-avatar>

    <h2 class="user-profile-page__title">{{ user.name || user.email }}</h2>

    <router-link
      :to="`/users/${user._id}/kudos/add`"
      tag="md-button"
      class="md-raised md-accent user-profile-page__kudos-btn">
      Похвалить
    </router-link>

    <div
      v-if="userKudos && userKudos.length"
      class="user-kudos-list"
    >
      <h3>User Kudos</h3>

      <md-card
        v-for="userKudo in userKudos"
        :key="userKudo._id"
        class="user-kudos-list__item"
        md-with-hover
      >
        <md-card-header>
          <md-avatar>
            <img
              v-if="userKudo.user_from.avatar"
              :src="userKudo.user_from.avatar"
            >
            <md-icon v-else>face</md-icon>
          </md-avatar>

          <div class="md-title">{{ userKudo.user_from.name || userKudo.user_from.email }}</div>
          <div class="md-subhead">Date: {{ userKudo.created_at }}</div>
          <hr>
          <div
            v-if="userKudo.kudos_tag_ids"
            class="md-subhead"
          >
            <p>Category: {{ userKudo.kudos_category.name }}</p>
            <p>Tags: {{ userKudo.kudos_tags.map(kt => kt.name).join(', ') }}</p>
          </div>
        </md-card-header>

        <md-card-content>
          {{ userKudo.text }}
        </md-card-content>
      </md-card>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as usersStoreName } from '@/store/users';
import { name as kudosStoreName } from '@/store/kudos';
import { User } from '@/store/auth/types';
import { Kudos as KudosItem } from '@/store/kudos/types';

const Users = namespace(usersStoreName);
const Kudos = namespace(kudosStoreName);

@Component
export default class UserProfilePage extends Vue {
  @Users.Action getUser!: Function;
  @Kudos.Action getUserKudos!: Function;

  user: User | null = null;
  userKudos: KudosItem[] | null = null;

  async created() {
    const { id: userId } = this.$route.params;
    const userResponse = await this.getUser(userId);

    if (userResponse.status >= 400) {
      console.error(userResponse);
      return;
    }

    this.user = userResponse.data;

    const userKudosResponse = await this.getUserKudos(userId);

    if (userKudosResponse.status >= 400) {
      console.error(userKudosResponse);
      return;
    }

    this.userKudos = userKudosResponse.data;
  }
}
</script>

<style lang="scss" scoped>
  .user-profile-page {
    padding: 16px;

    &__title {
      margin-bottom: 20px;
    }

    &__kudos-btn {
      margin-left: -2px;
    }
  }

  .user-kudos-list {
    margin-top: 20px;

    &__item {
      margin-top: 20px;
    }
  }
</style>
