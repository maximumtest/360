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

const userToken = store.state.auth && store.state.auth.jwtToken;
if (userToken) {
  store.dispatch('auth/setTokenAndUser', { access_token: userToken });
}

axios.interceptors.request.use(
  (config) => {
    if (store.state.auth && store.state.auth.jwtToken) {
      config.headers.authorization = `Bearer ${store.state.auth.jwtToken}`;
    }

    return config;
  },
  error => Promise.reject(error),
);

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!store.state.auth.user) {
      next({ path: '/auth' });
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
