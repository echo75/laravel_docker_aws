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

export const useUserStore = defineStore("User", {
  actions: {
    async signup(firstName, surName, email, password) {
      await ensureCsrfCookie();
      await axios.post("/users", {
        firstName,
        surName,
        email,
        password,
      });
    },
  },
}); // end of store
