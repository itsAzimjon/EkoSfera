import Login from '../layouts/Login.vue'
import MainLayout from '../layouts/MainLayout.vue'
import Index from '../pages/Index.vue'
import Users from '../pages/user/Users.vue'
import Organizations from '../pages/organization/Organizations.vue'
import Error404 from '../layouts/Error404.vue'
import { getItem } from '../helpers/Storage'

const routes = [
  {
    name:"login",
    path: '/login',
    component: () => Login,
  },
  {
    name:"main",
    component: () =>MainLayout,
    path:"",
    children: [
      {
        path: '/',
        name: 'dashboard',
        component: () => Index,
      },
      {
        path: '/organizations',
        name: 'organizations',
        component: () => Organizations,
      },
      {
        path: '/users',
        name: 'users',
        component: () => Users,
      },
      
    ],
  },
  {
    name:"eror",
    path: '/:catchAll(.*)*',
    component: () => Error404,
  },
  
]

export default routes
