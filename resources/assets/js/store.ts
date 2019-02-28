import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
  },
  mutations: {
    setItem(state: any, { item, value }) {
      state[item] = value;
    },
  },
  actions: {
  },
});
