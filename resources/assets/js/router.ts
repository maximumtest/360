import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';

Vue.use(Router);

export default new Router({
  mode: 'history',
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
    {
      path: '/reviews',
      component: () => import(/* webpackChunkName: "reviews" */ './views/Reviews.vue'),
      children: [
        {
          path: '',
          name: 'reviews',
          component: () => import(/* webpackChunkName: "reviewsIndex" */ './views/reviews/Index.vue'),
          meta: {
            title: 'Ревью',
          },
        },
        {
          path: ':id',
          name: 'reviews-id',
          component: () => import(/* webpackChunkName: "reviewPage" */ './views/reviews/Id.vue'),
          meta: {
            title: 'Ревью',
          },
          children: [
            {
              path: 'users/:userId',
              name: 'reviews-id-users-userId',
              component: () => import(/* webpackChunkName: "reviewUserPage" */ './views/reviews/users/UserId.vue'),
              meta: {
                title: 'Ревью',
              },
            },
          ],
        },
      ],
    },
  ],
});
