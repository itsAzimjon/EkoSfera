import { boot } from "quasar/wrappers";
import axios from "axios";
import { getItem } from "../helpers/Storage";

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({ baseURL: "https://api.ekosfera.uz/api" });
// const api = axios.create({ baseURL: "http://127.0.0.1:8000/api" });
axios.interceptors.request.use((config) => {
  const token = getItem("token");
  const authorization = token ? "Bearer " + token : "";
  config.headers.Authorization = authorization;
  return config;
});

import { Notify } from "quasar";

const e = (mess) => {
  Notify.create({
    type: "negative",
    message: mess,
    position: "bottom-right",
    progress: true,
    actions: [
      {
        icon: "close",
        size: "sm",
        color: "white",
        handler: () => {
          /* ... */
        },
      },
    ],
  });
};
const s = (mess) => {
  Notify.create({
    type: "positive",
    message: mess,
    position: "bottom-right",
    progress: true,
    actions: [
      {
        icon: "close",
        size: "sm",
        color: "white",
        handler: () => {
          /* ... */
        },
      },
    ],
  });
};

const w = (mess) => {
  Notify.create({
    type: "warning",
    message: mess,
    position: "bottom-right",
    progress: true,
    actions: [
      {
        icon: "close",
        size: "sm",
        color: "white",
        handler: () => {
          /* ... */
        },
      },
    ],
  });
};

const i = (mess) => {
  Notify.create({
    type: "info",
    message: mess,
    position: "bottom-right",
    progress: true,
    actions: [
      {
        icon: "close",
        size: "sm",
        color: "white",
        handler: () => {
          /* ... */
        },
      },
    ],
  });
};
const checkstatus = (status) => {
  if (status == 401) {
    window.location = "/";
  }
};
const getter = (arr, namuna) => {
  for (let i = 0; i < arr.length; i++) {
    if (arr[i].children != null) {
      for (let j = 0; j < arr[i].children.length; j++) {
        if (arr[i].children[j].url === namuna) return arr[i].children[j];
      }
    } else if (arr[i].url === namuna) return arr[i];
  }
};
const checkRole = (url, p) => {
  let res = getter(p, url);
  if (res != null) {
    return res;
  } else {
    window.location = "/";
  }
};
export default boot(({ app, store }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api
  app.config.globalProperties.$axios = axios;
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api;
  app.config.globalProperties.$e = e;
  app.config.globalProperties.$s = s;
  app.config.globalProperties.$w = w;
  app.config.globalProperties.$i = i;
  app.config.globalProperties.$checkstatus = checkstatus;
  app.config.globalProperties.$getter = getter;
  app.config.globalProperties.$checkRole = checkRole;
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
});

export { api };
