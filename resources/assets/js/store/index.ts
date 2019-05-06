import Vue from 'vue';
import Vuex from 'vuex';
import * as auth from './auth';
import * as reviews from './reviews';
import createPersistedState from 'vuex-persistedstate';

const presistedState = createPersistedState({
  key: 'store360',
});

Vue.use(Vuex);

export default new Vuex.Store({
  plugins: [presistedState],
  mutations: {
    setItem(state: any, { item, value }) {
      state[item] = value;
    },
  },
  actions: {
  },
  modules: {
    [auth.name]: auth,
    [reviews.name]: reviews,
  },
});
