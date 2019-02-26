<template>
  <div class="reviews-page">
    <md-list
      :class="`reviews-page__nav_mode_${navMode}`"
      class="reviews-page__nav"
    >
      <md-list-item
        v-for="review in reviews"
        :key="`review-${review.id}`"
      >
        <router-link
          :to="{ name: 'reviews-id', params: { id: review.id } }"
          class="reviews-page__link"
        >
          <span class="md-list-item-text">{{ review.title }}</span>
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

declare interface Review {
  id: string,
  title: string,
}

@Component
export default class Reviews extends Vue {
  reviews: Review[] = [
    {
      id: '1',
      title: '2019-Q1',
    },
    {
      id: '2',
      title: '2019-Q2',
    },
  ];

  get navMode() {
    return this.$route.name === 'reviews' ? 'full' : 'short';
  }
};
</script>

<style lang="scss" scoped>
.reviews-page {
  display: flex;
  min-height: calc(100vh - 64px);

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
