import { MutationTree, ActionTree } from 'vuex';
import { UsersState } from './types';
import { RootState } from '@/store/types';
import axios from 'axios';

export const name: string = 'users';

export const namespaced: boolean = true;

export const state: UsersState = {
  users: undefined,
};

const setItem = (key: string) => (currentState: any, value: any) => currentState[key] = value;

export const mutations: MutationTree<UsersState> = {
  setUsers: setItem('users'),
};

export const actions: ActionTree<UsersState, RootState> = {
  async getUsers({ commit }, searchTerm: string) {
    try {
      const response = await axios.get(`/api/v1/users/filter?searchTerm=${searchTerm}`);

      commit('setUsers', response.data);

      return response;
    } catch (error) {
      return error.response;
    }
  },

  async getUser({ commit }, userId: string) {
    try {
      const response = await axios.get(`/api/v1/users/${userId}`);

      return response;
    } catch (error) {
      return error.response;
    }
  },
};
