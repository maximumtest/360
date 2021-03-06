import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.min.css';
import 'vue-material/dist/theme/default-dark.css';

import './assets/styles/base.scss';

import axios from 'axios';

import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store/';

Vue.use(VueMaterial);

Vue.config.productionTip = false;

axios.interceptors.request.use(
  (config) => {
    if (store.state.auth && store.state.auth.jwtToken) {
      config.headers.authorization = `Bearer ${store.state.auth.jwtToken}`;
    }

    return config;
  },
  error => Promise.reject(error),
);

const userToken = store.state.auth && store.state.auth.jwtToken;
if (userToken) {
  store.dispatch('auth/setTokenAndUser', { access_token: userToken });
}

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!store.state.auth.user) {
      next({ name: 'signIn' });
    } else {
      next();
    }
  } else {
    next();
  }
});

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');
