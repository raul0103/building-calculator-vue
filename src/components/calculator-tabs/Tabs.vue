<template>
  <ul class="calculator-tab__tabs">
    <li
      class="calculator-tab__tabs-item"
      v-for="tab in calculator_store.calculator_tabs"
      :key="tab.key"
    >
      <a
        :class="{ active: calculator_store.calculator_key_active == tab.key }"
        @click="setActiveCalc(tab.key)"
        >{{ tab.title }}</a
      >
    </li>
  </ul>
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";

export default {
  data() {
    return {
      calculator_store: useCalculatorStore(),
    };
  },
  mounted() {},
  methods: {
    setActiveCalc(type) {
      this.calculator_store.setActiveCalc(type);
    },
  },
};
</script>
