<template>
  <div
    v-if="!isLoading"
    class="review-page"
  >
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
          <span class="md-list-item-text">
            <template v-if="reviewResultByRespondentId(user._id) !== undefined">&#10003; </template>
            {{ user.name || user.email }}</span>
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
import { name as reviewResultsStoreName } from '@/store/review-results';
import { ReviewItem } from '@/store/reviews/types';
import { ReviewResult } from '@/store/review-results/types';

const Reviews = namespace(reviewsStoreName);
const ReviewResults = namespace(reviewResultsStoreName);

@Component
export default class ReviewPage extends Vue {
  @Reviews.Getter currentReview!: (reviewId: string) => ReviewItem | null | undefined;

  @ReviewResults.Action getReviewResults!: (reviewId: string) => Response;

  @ReviewResults.State reviewResults!: ReviewResult[];

  @ReviewResults.Getter reviewResultByRespondentId!: ReviewResult | undefined;


  review: ReviewItem | null | undefined = null;

  isLoading: boolean = true;

  get navMode() {
    return this.$route.name === 'reviews-id' ? 'full' : 'short';
  }

  async created() {
    const reviewId = this.$route.params.id;

    await this.getReviewResults(reviewId);
    this.review = this.currentReview(reviewId);

    this.isLoading = false;
  }

  updated() {
    this.review = this.currentReview(this.$route.params.id);
  }
}
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
