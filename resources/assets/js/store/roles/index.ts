import { ActionTree } from 'vuex';
import { RolesState } from './types';
import { RootState } from '@/store/types';
import axios from 'axios';

export const name: string = 'roles';

export const namespaced: boolean = true;

export const state: RolesState = {};

export const actions: ActionTree<RolesState, RootState> = {
  async getRoles(context: object) {
    try {
      return await axios.get('/api/v1/roles');
    } catch (error) {
      return error.response;
    }
  },
};
