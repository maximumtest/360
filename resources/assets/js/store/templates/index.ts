import { ActionTree } from 'vuex';
import { TemplatesState } from './types';
import { RootState } from '@/store/types';
import axios from 'axios';

export const name: string = 'templates';

export const namespaced: boolean = true;

export const state: TemplatesState = {};

export const actions: ActionTree<TemplatesState, RootState> = {
  async getTemplate({ commit }, templateId: string) {
    try {
      return await axios.get(`/api/v1/templates/${templateId}`);
    } catch (error) {
      return error.response;
    }
  },
};
