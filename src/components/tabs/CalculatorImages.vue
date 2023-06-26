<template>
  {{ calculator_store.calculator_data_active?.images }}

  <div>
    <img :src="calculator_store.calculator_data_active?.images[0]" />
  </div>
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";
import { useVariablesStore } from "@/stores/variables.js";

export default {
  data() {
    return {
      calculator_store: useCalculatorStore(),
      variables_store: useVariablesStore(),
    };
  },
  methods: {},
};
</script>
