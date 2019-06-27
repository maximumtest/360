import Vue from 'vue';
import Vuex from 'vuex';
import * as auth from './auth';
import * as users from './users';
import * as kudos from './kudos';
import * as reviews from './reviews';
import * as templates from './templates';
import * as reviewResults from './review-results';
import * as roles from './roles';
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
    [users.name]: users,
    [kudos.name]: kudos,
    [reviews.name]: reviews,
    [templates.name]: templates,
    [reviewResults.name]: reviewResults,
    [roles.name]: roles,
  },
});
