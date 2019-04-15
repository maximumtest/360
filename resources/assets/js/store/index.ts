import Vue from 'vue';
import Vuex from 'vuex';
import * as auth from './auth';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export default new Vuex.Store({
  plugins: [createPersistedState()],
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
