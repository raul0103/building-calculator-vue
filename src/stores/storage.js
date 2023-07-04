/**
 * Работа с данными пользователя
 *
 * Например для сохранения данных из обратной формы для использования имени, номера и почты при регенерации PDF
 */

// import { ref } from "vue";
import { defineStore } from "pinia";

export const useStorageStore = defineStore("storage", () => {
  // const storage = ref({});
  const storage_key = "calculator-a23nof2cs";

  function getStorage(key = null) {
    const data = JSON.parse(localStorage.getItem(storage_key));

    if (key && data) {
      if (key in data) return data[key];
      else return null;
    } else {
      return data;
    }
  }

  function setStorage(data) {
    const value = JSON.stringify(data);
    localStorage.setItem(storage_key, value);
  }

  return {
    // storage,
    getStorage,
    setStorage,
  };
});
