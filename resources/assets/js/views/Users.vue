<template>
  <div class="users-page">
    <md-field>
      <label>ФИО / email</label>
      <md-input @input="getUsers"></md-input>
    </md-field>

    <ul class="users-list">
      <li
        v-for="user in users"
        :key="user._id"
        class="users-list__item"
      >
        <router-link :to="`/users/${user._id}`">{{ user.name || user.email }}</router-link>
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as usersStoreName } from '@/store/users';
import { User } from '@/store/auth/types';

const Users = namespace(usersStoreName);

@Component
export default class UsersPage extends Vue {
  @Users.Action getUsers!: Function;
  @Users.State users!: User[];
}
</script>

<style lang="scss" scoped>
  .users-page {
    padding: 0 16px;
  }

  .users-list {
    list-style-type: none;
    margin: 0;
    padding: 0;

    &__item {
      margin: 8px 0;
    }
  }
</style>
