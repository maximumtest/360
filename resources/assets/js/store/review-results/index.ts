import { ActionTree } from 'vuex';
import { ReviewResultsState, CreateReviewResultRequest } from './types';
import { RootState } from '@/store/types';
import axios from 'axios';

export const name: string = 'review-results';

export const namespaced: boolean = true;

export const state: ReviewResultsState = {};

export const actions: ActionTree<ReviewResultsState, RootState> = {
  async saveReviewResult(context: Object, reviewResultRequest: CreateReviewResultRequest) {
    try {
      return await axios.post('/api/v1/review-results', reviewResultRequest);
    } catch (error) {
      return error.response;
    }
  },
};
