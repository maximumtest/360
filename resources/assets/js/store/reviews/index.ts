import { MutationTree, ActionTree, GetterTree } from 'vuex';
import { ReviewState, ReviewItem } from './types';
import { RootState } from '@/store/types';
import axios from 'axios';

export const name: string = 'reviews';

export const namespaced: boolean = true;

export const state: ReviewState = {
  reviews: null,
};

const setItem = (key: string) => (currentState: any, value: any) => currentState[key] = value;

export const mutations: MutationTree<ReviewState> = {
  setReviews: setItem('reviews'),
};

export const actions: ActionTree<ReviewState, RootState> = {
  async loadReviews({ commit }) {
    try {
      const response = await axios.get('/api/v1/reviews');

      commit('setReviews', response.data);

      return response;
    } catch (error) {
      return error.response;
    }
  },
};

export const getters: GetterTree<ReviewState, RootState> = {
  currentReview(state): (reviewId: string) => ReviewItem | null | undefined {
    return (reviewId: string) => {
      return state.reviews
        ? state.reviews.find(review => review._id === reviewId)
        : null;
    };
  },
};
