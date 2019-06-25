import { ActionTree, MutationTree, GetterTree } from 'vuex';
import { ReviewResultsState, CreateReviewResultRequest, ReviewResult } from './types';
import { RootState } from '@/store/types';
import axios, { AxiosResponse } from 'axios';

export const name: string = 'review-results';

export const namespaced: boolean = true;

const setItem = (key: string) => (currentState: any, value: any) => currentState[key] = value;

export const mutations: MutationTree<ReviewResultsState> = {
  setReviewResults: setItem('reviewResults'),
};

export const state: ReviewResultsState = {
  reviewResults: [],
};

export const actions: ActionTree<ReviewResultsState, RootState> = {
  async saveReviewResult({ commit, state }, reviewResultRequest: CreateReviewResultRequest): Promise<any> {
    try {
      const response: AxiosResponse = await axios.post('/api/v1/review-results', reviewResultRequest);

      commit('setReviewResults', [
        ...state.reviewResults,
        response.data,
      ]);

      return response;
    } catch (error) {
      return error.response;
    }
  },

  async getReviewResults({ commit }, reviewId: string): Promise<any> {
    try {
      const response: AxiosResponse = await axios.get(`/api/v1/reviews/${reviewId}/review-results`);
      commit('setReviewResults', response.data);

      return response;
    } catch (error) {
      return error.response;
    }
  },
};

export const getters: GetterTree<ReviewResultsState, RootState> = {
  reviewResultByRespondentId: (state: ReviewResultsState) => (respondentId: string): ReviewResult | undefined => {
    return state.reviewResults.find(reviewResult => reviewResult.respondent_id === respondentId);
  },
};
