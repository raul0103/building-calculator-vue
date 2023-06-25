/**
 * Сохранются результаты расчетов по каждому калькулятору
 * Делается для хранения результатов при переключении табов
 * {calc_type:[results]}/ {tape:[{},{}]}
 */

import { ref } from "vue";
import { defineStore } from "pinia";

import { useCalculatorStore } from "@/stores/calculator";

export const useResultsStore = defineStore("results", () => {
  const results = ref({});

  const calculator_store = useCalculatorStore();

  function setResults(value) {
    results.value[calculator_store.calculator_key_active] = value;
  }

  function getResults() {
    return results.value[calculator_store.calculator_key_active];
  }

  return {
    results,
    setResults,
    getResults,
  };
});
