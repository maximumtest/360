<template>
  <div id="app">
    <v-app
      md-mode="fixed"
      md-waterfall
      class="app"
    >
      <!-- Шапка -->
      <v-toolbar class="app__header">
        <v-toolbar-title>
          <router-link
            to="/"
            class="app__logo"
          >
            360 MAXIMUM {{ pageTitle }}
          </router-link>
        </v-toolbar-title>

        <template v-if="user">
          <v-btn
            title="Профиль"
            to="/profile"
          >
            {{ user.email }}
          </v-btn>

          <v-btn
            title="Выйти"
            @click="signOut"
          >
            <v-icon>mdi-exit-run</v-icon>
          </v-btn>
        </template>
      </v-toolbar>

      <!-- Сайдбар -->
      <v-navigation-drawer
        class="sidebar"
        permanent
      >
        <v-list class="sidebar__nav">
          <v-subheader>Меню</v-subheader>
          <v-list-item-group
            v-model="item"
            color="primary"
          >
            <v-list-item
              v-for="(navItem, index) in navItems"
              :key="`nav-item-${index}`"
              :to="navItem.path"
              class="sidebar__link"
            >
              <v-icon class="sidebar__link-icon">
                mdi-{{ navItem.icon }}
              </v-icon>
              <span
                class="md-list-item-text"
                v-text="navItem.text"
              />
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>

      <div class="app__content">
        <transition
          name="router-anim"
          mode="out-in"
        >
          <router-view />
        </transition>
      </div>
    </v-app>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as authStoreName } from '@/store/auth';
import { User } from '@/store/auth/types';

const Auth = namespace(authStoreName);

@Component
export default class DefaultLayout extends Vue {
  @Auth.State user!: User;

  @Auth.Action logout!: Function;

  navItems: any[] = [
    {
      icon: 'home',
      text: 'Главная',
      path: {
        name: 'main',
      },
    },
    {
      icon: 'clipboard-list-outline',
      text: 'Ревью',
      path: {
        name: 'reviews',
      },
    },
    {
      icon: 'account-group-outline',
      text: 'Пользователи',
      path: {
        name: 'users',
      },
    },
    {
      icon: 'lock-open-outline',
      text: 'Админка',
      path: {
        name: 'admin',
      },
    },
  ];

  get pageTitle() {
    return this.$route.meta.title ? `- ${this.$route.meta.title}` : '';
  }

  async signOut() {
    const response = await this.logout();

    if (response.status === 200) {
      this.$router.push({ path: '/', query: { logout: 'logout' } });
    }
  }
}
</script>

<style lang="scss" scoped>
.app {
  min-height: 100vh;

  &__logo {
    text-decoration: none;
    flex: 1;
    &:hover {
      text-decoration: none;
    }
  }

  &__content {
    padding: 0;
  }
}

.sidebar {
  &__link {
    align-items: center;
    color: inherit !important;
    display: flex;
    &:hover {
      text-decoration: none;
    }
  }

  &__link-icon {
    margin-right: 12px;
  }
}

.user-icon {
  padding-right: 10px;
}
</style>
