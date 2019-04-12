import Vue from 'vue';
import Vuex from 'vuex';
import * as auth from './auth';

Vue.use(Vuex);

export default new Vuex.Store({
  mutations: {
    setItem(state: any, { item, value }) {
      state[item] = value;
    },
  },
  actions: {
  },
  modules: {
    [auth.name]: auth,
  },
});
