import { defineStore } from "pinia";

export const useVariablesStore = defineStore("variables", () => {
  const api_url = import.meta.env.DEV
    ? "http://calculator/calculator"
    : "calculator";
  // const image_url = import.meta.env.DEV ? "http://calculator/" : "";
  return {
    api_url,
    // image_url,
  };
});
