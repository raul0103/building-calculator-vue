/**
 * Данные обратной формы
 */

import { ref } from "vue";
import { defineStore } from "pinia";

export const useCallbackFormStore = defineStore("callback-form", () => {
  const storage_key = "calculator-cf-a23nof2cs";
  const form_data = ref({});
  const form_sent = ref(false);

  /** Если есть данные в памяти то переопределить переменные */
  if (JSON.parse(localStorage.getItem(storage_key))) {
    form_data.value = JSON.parse(localStorage.getItem(storage_key));
    form_sent.value = true;
  }

  /** работа со статусом отправки */
  const sent = {
    get: () => form_sent.value,
    set: (is_sent) => {
      form_sent.value = is_sent;
    },
  };

  /** работа с данными */
  const data = {
    get: (key = null) => {
      if (key) {
        if (key in form_data.value) return form_data.value[key];
        else return null;
      } else {
        return form_data.value;
      }
    },
    set: (new_data) => {
      form_data.value = new_data;
      localStorage.setItem(storage_key, JSON.stringify(new_data));
    },
    remove: () => {
      localStorage.removeItem(storage_key);
      form_data.value = {};
    },
  };

  return {
    sent,
    data,
  };
});
