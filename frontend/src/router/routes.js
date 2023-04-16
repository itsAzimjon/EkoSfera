const routes = [
  {
    name:"login",
    path: '/login',
    component: () => import('layouts/Login.vue'),
  },
  {
    name:"main",
    component: () =>import('layouts/MainLayout.vue'),
    children: [
      {
        path: '/',
        name: 'dashboard',
        component: () => import('pages/Index.vue'),
      },
      {
        path: '/organizations',
        name: 'organizations',
        component: () => import('pages/organization/Organizations.vue'),
      },
      {
        path: '/users',
        name: 'users',
        component: () => import('pages/user/Users.vue'),
      },
      {
        path: '/shartnomalar',
        name: 'shartnomalar',
        component: () => import('pages/shartnomalar/Shartnoma.vue'),
      },
      {
        path: '/talonlar',
        name: 'talonlar',
        component: () => import('pages/talonlar/Talonlar.vue'),
      },
    ],
  },
  {
    name:"eror",
    path: '/:catchAll(.*)*',
    component: () => import('layouts/Error404.vue'),
  },
  
]

export default routes
