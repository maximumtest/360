import { MutationTree, ActionTree } from 'vuex';
import { AuthState, LoginRequest, VerifyEmailRequest, TokenResponse } from './types';
import { RootState } from '@/store/types';
import axios from 'axios';

export const name: string = 'auth';

export const namespaced: boolean = true;

export const state: AuthState = {
  jwtToken: undefined,
  expiresIn: undefined,
  user: undefined,
};

const setItem = (key: string) => (currentState: any, value: any) => currentState[key] = value;

export const mutations: MutationTree<AuthState> = {
  setJwtToken: setItem('jwtToken'),
  setExpiresIn: setItem('expiresIn'),
  setUser: setItem('user'),
};

export const actions: ActionTree<AuthState, RootState> = {
  async login({ dispatch }, payload: LoginRequest) {
    try {
      const response = await axios.post('/api/v1/auth/login', payload);

      await dispatch('setTokenAndUser', response.data);

      return response;
    } catch (error) {
      return error.response;
    }
  },

  async verifyEmail({ dispatch }, payload: VerifyEmailRequest) {
    try {
      const response = await axios.post('/api/v1/auth/email/verification', payload);

      await dispatch('setTokenAndUser', response.data);

      return response;
    } catch (error) {
      return error.response;
    }
  },

  async setTokenAndUser({ state, commit, dispatch }, { access_token, expires_in }: TokenResponse) {
    commit('setJwtToken', access_token);
    commit('setExpiresIn', expires_in);

    const userData = await dispatch('getUserInfo');

    commit('setUser', userData);
  },

  async getUserInfo() {
    try {
      const { data } = await axios.get('/api/v1/auth/me');

      return data;
    } catch (e) {
      throw e;
    }
  },
};
