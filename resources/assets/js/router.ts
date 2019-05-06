import Vue from 'vue';
import Router from 'vue-router';
import Main from './views/Main.vue';

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
      path: '/auth',
      name: 'auth',
      component: () => import(/* webpackChunkName: "auth" */ './views/Auth.vue'),
      meta: {
        layout: 'auth',
      },
    },
    {
      path: '/verify-email/:code',
      name: 'verify-email',
      component: () => import(/* webpackChunkName: "verifyEmail" */ './views/VerifyEmail.vue'),
      meta: {
        layout: 'auth',
      },
    },
    {
      path: '/users',
      name: 'users',
      component: () => import(/* webpackChunkName: "auth" */ './views/Users.vue'),
      meta: {
        title: 'Пользователи',
      },
    },
    {
      path: '/users/:id',
      name: 'users-profile',
      component: () => import(/* webpackChunkName: "auth" */ './views/users/Profile.vue'),
      meta: {
        title: 'Профиль',
      },
    },
    {
      path: '/users/:id/kudos/add',
      name: 'user-add-kudos',
      component: () => import(/* webpackChunkName: "auth" */ './views/users/kudos/Add.vue'),
      meta: {
        title: 'Добавить kudos',
      },
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
