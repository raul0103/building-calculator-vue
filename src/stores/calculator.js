import { ref } from "vue";
import { defineStore } from "pinia";
import axios from "axios";

import { useVariablesStore } from "@/stores/variables";

export const useCalculatorStore = defineStore("calculator", () => {
  const calculator_tabs = ref(null);
  const calculator_data = ref(null);
  const calculator_key_active = ref(null);
  const calculator_data_active = ref(null);
  const calculator_form_name = "calculator";

  /**
   * Определяет активный калькулятор.
   * Используется при выборе табов
   */
  function setActiveCalc(type) {
    calculator_key_active.value = type;
    calculator_data_active.value = calculator_data.value[type];
  }

  /**
   * Данные для отображеиня калькулятора на странице
   * Запускается при загрузке приложения в App.vue
   */
  function getCalculatorDataOnPageLoad() {
    const variables_store = useVariablesStore();
    axios
      .get(`${variables_store.api_url}/calculator/api/config.php`)
      .then((response) => {
        calculator_data.value = response.data.calculator_data;
        calculator_tabs.value = response.data.calculator_tabs;
        calculator_key_active.value = response.data.calculator_active_key;
        calculator_data_active.value = response.data.calculator_data_active;
      });
  }

  return {
    calculator_tabs,
    calculator_data,
    calculator_key_active,
    calculator_data_active,
    calculator_form_name,
    setActiveCalc,
    getCalculatorDataOnPageLoad,
  };
});
