<template>
  <div class="review-page">
    <md-list
      :class="`review-page__nav_mode_${navMode}`"
      class="review-page__nav"
    >
      <md-list-item
        v-for="user in review.users"
        :key="`user-${user._id}`"
      >
        <router-link
          :to="{
            name: 'reviews-id-users-userId',
            params: {
              id: $route.params.id,
              userId: user._id,
            },
          }"
          class="review-page__link"
        >
          <span class="md-list-item-text">{{ user.name || user.email }}</span>
        </router-link>
      </md-list-item>
    </md-list>
    <transition
      name="router-anim"
      mode="out-in"
    >
      <router-view :key="$route.fullPath" />
    </transition>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as reviewsStoreName } from '@/store/reviews';
import { ReviewItem } from '@/store/reviews/types';

const Reviews = namespace(reviewsStoreName);

@Component
export default class ReviewPage extends Vue {
  @Reviews.Getter currentReview!: (reviewId: string) => ReviewItem | null | undefined;

  review: ReviewItem | null | undefined = null;

  get navMode() {
    return this.$route.name === 'reviews-id' ? 'full' : 'short';
  }

  created() {
    this.review = this.currentReview(this.$route.params.id);
  }

  updated() {
    this.review = this.currentReview(this.$route.params.id);
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
