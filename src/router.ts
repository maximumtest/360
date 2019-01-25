import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      // TSLint warning here. Waiting for https://github.com/buzinas/tslint-eslint-rules/issues/252
      // tslint:disable-next-line
      component: () => import(/* webpackChunkName: "about" */ './views/About.vue'),
    },
  ],
});
