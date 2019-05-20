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
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: '/auth',
      name: 'auth',
      component: () => import(/* webpackChunkName: "auth" */ './views/Auth.vue'),
      meta: {
        layout: 'auth',
      },
      children: [
        {
          path: 'sign-in',
          name: 'signIn',
          component: () => import(/* webpackChunkName: "signIn" */ './views/auth/SignIn.vue'),
          meta: {
            layout: 'auth',
          },
        },
        {
          path: 'verify-email/:code',
          name: 'verify-email',
          component: () => import(/* webpackChunkName: "verifyEmail" */ './views/auth/VerifyEmail.vue'),
          meta: {
            layout: 'auth',
          },
        },
        {
          path: 'forgot-password',
          name: 'forgotPassword',
          component: () => import(/* webpackChunkName: "resetPassword" */ './views/auth/ForgotPassword.vue'),
          meta: {
            layout: 'auth',
          },
        },
        {
          path: 'password-reset/:code',
          name: 'passwordReset',
          component: () => import(/* webpackChunkName: "passwordReset" */ './views/auth/PasswordReset.vue'),
          meta: {
            layout: 'auth',
          },
        },
      ],
    },
    {
      path: '/users',
      name: 'users',
      component: () => import(/* webpackChunkName: "auth" */ './views/Users.vue'),
      meta: {
        title: 'Пользователи',
        requiresAuth: true,
      },
    },
    {
      path: '/users/:id',
      name: 'users-profile',
      component: () => import(/* webpackChunkName: "auth" */ './views/users/Profile.vue'),
      meta: {
        title: 'Профиль',
        requiresAuth: true,
      },
    },
    {
      path: '/users/:id/kudos/add',
      name: 'user-add-kudos',
      component: () => import(/* webpackChunkName: "auth" */ './views/users/kudos/Add.vue'),
      meta: {
        title: 'Добавить kudos',
        requiresAuth: true,
      },
    },
    {
      path: '/reviews',
      component: () => import(/* webpackChunkName: "reviews" */ './views/Reviews.vue'),
      meta: {
        requiresAuth: true,
      },
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
