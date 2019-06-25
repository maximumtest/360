import { MutationTree, ActionTree } from 'vuex';
import { UsersState, CreateUserRequest, UpdateUserRequest } from './types';
import { RootState } from '@/store/types';
import axios, { AxiosResponse } from 'axios';

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
      const response = await axios.get('/api/v1/users/filter', {
        params: {
          searchTerm,
        },
      });

      commit('setUsers', response.data);

      return response;
    } catch (error) {
      return error.response;
    }
  },

  async getAllUsers(context: object): Promise<AxiosResponse> {
    try {
      return await axios.get('/api/v1/users');
    } catch (error) {
      return error.response;
    }
  },

  async createUser(context: object, request: CreateUserRequest): Promise<AxiosResponse> {
    try {
      return await axios.post('/api/v1/users', request);
    } catch (error) {
      return error.response;
    }
  },

  async updateUser(context: object, request: UpdateUserRequest): Promise<AxiosResponse> {
    try {
      return await axios.patch(`/api/v1/users/${request.userId}`, request);
    } catch (error) {
      return error.response;
    }
  },

  async deleteUser(context: object, userId: string): Promise<AxiosResponse> {
    try {
      return await axios.delete(`/api/v1/users/${userId}`);
    } catch (error) {
      return error.response;
    }
  },

  async getUser({ commit }, userId: string) {
    try {
      return await axios.get(`/api/v1/users/${userId}`);
    } catch (error) {
      return error.response;
    }
  },

  async updateProfile({ commit, rootState }, request: FormData) {
    try {
      const response = await axios.post('/api/v1/users/update-profile', request, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      const { user } = rootState.auth;
      user!.avatar = response.data.avatar;

      commit('auth/setUser', user, { root: true });

      return response;
    } catch (error) {
      return error.response;
    }
  },
};
