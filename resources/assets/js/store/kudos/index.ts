import { MutationTree, ActionTree } from 'vuex';
import { KudosState, CreateKudosRequest } from './types';
import { RootState } from '@/store/types';
import axios from 'axios';

export const name: string = 'kudos';

export const namespaced: boolean = true;

export const state: KudosState = {
  currentUserKudos: null,
  kudosTags: null,
  kudosCategories:null,
};

const setItem = (key: string) => (currentState: any, value: any) => currentState[key] = value;

export const mutations: MutationTree<KudosState> = {
  setCurrentUserKudos: setItem('currentUserKudos'),
  setKudosTags: setItem('kudosTags'),
  setKudosCategories: setItem('kudosCategories'),
};

export const actions: ActionTree<KudosState, RootState> = {
  async getKudosCategories({ commit }) {
    try {
      const response = await axios.get('/api/v1/kudos-categories');

      commit('setKudosCategories', response.data);

      return response;
    } catch (error) {
      return error.response;
    }
  },

  async getKudosTags({ commit }, tagName?: string) {
    const url = `/api/v1/kudos-tags/${tagName ? `filter?name=${tagName}` : ''}`;

    try {
      const response = await axios.get(url);

      commit('setKudosTags', response.data);
    } catch (error) {
      return error.response;
    }
  },

  async getUserKudos({ commit }, userId: string) {
    try {
      const response = await axios.get(`/api/v1/users/${userId}/kudos`);

      commit('setCurrentUserKudos', response.data);

      return response;
    } catch (error) {
      return error.response;
    }
  },

  async createKudos({ commit }, payload: CreateKudosRequest) {
    try {
      const response = await axios.post(`/api/v1/users/${payload.userToId}/kudos`, payload);

      return response;
    } catch (error) {
      return error.response;
    }
  },
};
