import Vue from 'vue';
import Router from 'vue-router';
import Main from './views/Main';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'main',
      component: Main,
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
