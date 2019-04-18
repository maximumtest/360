<template>
  <md-app
    md-mode="fixed"
    md-waterfall
    class="app"
  >
    <!-- Шапка -->
    <md-app-toolbar class="md-primary app__header">
      <router-link
        to="/"
        class="md-title app__logo"
      >
        360 MAXIMUM {{ pageTitle }}
      </router-link>
      <span
        v-if="user"
        v-text="user.email"
      />
    </md-app-toolbar>

    <!-- Сайдбар -->
    <md-app-drawer
      md-permanent="full"
      class="sidebar"
    >
      <md-list class="sidebar__nav">
        <md-list-item
          v-for="(navItem, index) in navItems"
          :key="`nav-item-${index}`"
        >
          <router-link
            :to="navItem.path"
            class="sidebar__link"
          >
            <md-icon class="sidebar__link-icon">{{ navItem.icon }}</md-icon>
            <span class="md-list-item-text">{{ navItem.text }}</span>
          </router-link>
        </md-list-item>
      </md-list>
    </md-app-drawer>

    <md-app-content class="app__content">
      <transition
        name="router-anim"
        mode="out-in"
      >
        <router-view/>
      </transition>
    </md-app-content>
  </md-app>
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

  navItems: any[] = [
    {
      icon: 'home',
      text: 'Главная',
      path: {
        name: 'main',
      },
    },
    {
      icon: 'list',
      text: 'Ревью',
      path: {
        name: 'reviews',
      },
    },
  ];

  get pageTitle() {
    return this.$route.meta.title ? `- ${this.$route.meta.title}` : '';
  }
};
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

