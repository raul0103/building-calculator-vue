import { defineStore } from "pinia";

export const useVariablesStore = defineStore("variables", () => {
  const api_url =
    process.env.NODE_ENV == "development" ? "http://calculator" : "calculator";

  return {
    api_url,
  };
});
