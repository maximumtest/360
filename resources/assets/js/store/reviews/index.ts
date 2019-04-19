import { MutationTree, ActionTree } from 'vuex';
import { ReviewState } from './types';
import { RootState } from '@/store/types';
import axios from 'axios';

export const name: string = 'auth';

export const namespaced: boolean = true;

export const state: ReviewState = {
  reviews: [],
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
