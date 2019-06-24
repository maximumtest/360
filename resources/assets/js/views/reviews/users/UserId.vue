<template>
  <div class="review-user-page" v-if="user && template">
    <h1 class="md-headline">Пользователь {{ user.name || user.email }}</h1>

    <form
      class="review-form"
      @submit.prevent="onSaveReviewResult"
    >
      <div
        v-for="question in template.questions"
        :key="question._id"
        class="review-form__question-row"
      >
        <p
          class="review-form__question md-title"
          v-text="question.text"
        />

        <component
          :is="fields[question.question_type.name]"
          :question="question"
          v-model="formData[question._id].answer"
          class="review-form__answer"
          :disabled="reviewResult !== undefined"
          :answer-value="getAnswer(question._id)"
        />
      </div>

      <md-button
        v-if="reviewResult === undefined"
        class="md-raised md-accent"
        type="submit"
      >
        Send
      </md-button>
    </form>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as usersStoreName } from '@/store/users';
import { name as reviewsStoreName } from '@/store/reviews';
import { name as templatesStoreName } from '@/store/templates';
import { name as reviewResultsStoreName } from '@/store/review-results';
import { User } from '@/store/auth/types'
import { ReviewItem } from '@/store/reviews/types';
import { Template } from '@/store/templates/types';
import { CreateReviewResultRequest, ReviewResult } from '@/store/review-results/types';
import Radio from './form/Radio.vue';
import Checkbox from './form/Checkbox.vue';
import SelectField from './form/SelectField.vue';
import TextInput from './form/TextInput.vue';
import TextareaField from './form/TextareaField.vue';

const Users = namespace(usersStoreName);
const Reviews = namespace(reviewsStoreName);
const Templates = namespace(templatesStoreName);
const ReviewResults = namespace(reviewResultsStoreName);

@Component({
  components: {
    Radio,
    Checkbox,
    SelectField,
    TextInput,
    TextareaField,
  },
})
export default class ReviewUserPage extends Vue {
  @Users.Action getUser!: (userId: string) => any;
  @Templates.Action getTemplate!: (templateId: string) => any;
  @ReviewResults.Action saveReviewResult!: (reviewResultRequest: CreateReviewResultRequest) => any;
  @ReviewResults.Getter reviewResultByRespondentId!: (respondentId: string) => ReviewResult | undefined;

  @Reviews.Getter currentReview!: (reviewId: string) => ReviewItem | null | undefined;

  user?: User | null = null;
  template?: Template | null = null;

  fields: Object = {
    radio: Radio,
    checkbox: Checkbox,
    select: SelectField,
    text: TextInput,
    textarea: TextareaField,
  };

  formData: any = {};

  respondentId: string = '';
  reviewId: string = '';

  reviewResult: ReviewResult | undefined | null = null;

  async created() {
    const { userId, id: reviewId } = this.$route.params;

    this.respondentId = userId;
    this.reviewId = reviewId;

    const userResponse = await this.getUser(userId);

    if (userResponse.status >= 400) {
      console.error('Error while getting user information');
    } else {
      this.user = userResponse.data;
    }

    const review: ReviewItem | null | undefined = this.currentReview(reviewId);

    const templateResponse = await this.getTemplate(review!.template_id);

    if (templateResponse.status >= 400) {
      console.error('Error while getting template data');
    } else {
      this.template = templateResponse.data;

      this.template!.questions!.forEach(({_id: questionId}) => this.formData[questionId] = {
        question_id: questionId,
        answer: null,
      });
    }

    this.reviewResult = this.reviewResultByRespondentId(this.respondentId);
  }

  async onSaveReviewResult() {
    if (this.reviewResult) {
      alert('This respondent is already reviewed');
    }

    const reviewResultRequest: CreateReviewResultRequest = {
      review_id: this.reviewId,
      respondent_id: this.respondentId,
      answers: Object.values(this.formData),
    };

    const response = await this.saveReviewResult(reviewResultRequest);

    if (response.status >= 400) {
      console.error('Error while saving review result', response);
    } else {
      alert('Review result saved');

      this.$router.push({
        name: 'reviews-id',
        params: {
          id: this.reviewId,
        },
      });
    }
  }

  getAnswer(questionId: string): any {
    if (this.reviewResult) {
      const answerData = this.reviewResult.answers.find(answer => answer.question_id === questionId) || null;

      if (answerData) {
        return answerData.answer;
      }
    }

    return null;
  }
};
</script>

<style lang="scss" scoped>
  .review-user-page {
    padding: 12px 24px;
  }

  .review-form {
    margin-top: 30px;

    &__question-row {
      margin-bottom: 20px;
    }

    &__question {
      margin-bottom: 5px;
      color: #faff94;
    }

    &__answer {
      padding-left: 30px;
    }
  }
</style>
