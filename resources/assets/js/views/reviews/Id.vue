<template>
  <div class="review-page">
    <md-list
      :class="`review-page__nav_mode_${navMode}`"
      class="review-page__nav"
    >
      <md-list-item
        v-for="user in users"
        :key="`user-${user.id}`"
      >
        <router-link
          :to="{
            name: 'reviews-id-users-userId',
            params: {
              id: $route.params.id,
              userId: user.id,
            },
          }"
          class="review-page__link"
        >
          <span class="md-list-item-text">{{ user.fullName }}</span>
        </router-link>
      </md-list-item>
    </md-list>
    <transition
      name="router-anim"
      mode="out-in"
    >
      <router-view/>
    </transition>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

declare interface User {
  id: string,
  fullName: string,
}

@Component
export default class ReviewPage extends Vue {
  users: User[] = [
    {
      id: '1',
      fullName: 'Тимоха Жиганов',
    },
    {
      id: '2',
      fullName: 'Жендос Колдин',
    },
  ];

  get navMode() {
    return this.$route.name === 'reviews-id' ? 'full' : 'short';
  }
};
</script>

<style lang="scss" scoped>
.review-page {
  display: flex;

  &__nav {
    border-right: 1px solid gray;
    transition: width .3s ease;

    &_mode {
      &_full {
        width: 240px;
      }

      &_short {
        width: 100px;
      }
    }
  }
}
</style>
