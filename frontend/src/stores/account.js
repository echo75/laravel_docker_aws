import { defineStore } from "pinia";
import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL;

const getSanctumBaseUrl = () => {
  if (import.meta.env.VITE_BACKEND_ORIGIN) {
    return import.meta.env.VITE_BACKEND_ORIGIN;
  }

  return import.meta.env.VITE_API_BASE_URL.replace(/\/api\/?$/, "");
};

const ensureCsrfCookie = async () => {
  await axios.get("/sanctum/csrf-cookie", {
    baseURL: getSanctumBaseUrl(),
  });
};

export const useAccountStore = defineStore("Account", {
  state: () => ({
    user: null,
  }),
  actions: {
    async fetchUser() {
      this.user = (await axios.get("/accounts/session", {})).data;
    },
    async login({ email, password }) {
      await ensureCsrfCookie();
      this.user = (await axios.post("/accounts/session", { email, password })).data;
    },
    async logout() {
      await ensureCsrfCookie();
      await axios.delete("/accounts/session", {});
      this.user = null;
    },
  },
}); // end of store
