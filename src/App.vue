<template>
  <div class="calculator-tab">
    <CalculatorTabs />
    <CalculatorTab />
  </div>
  <CalculatorSubmit />
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";

import CalculatorTabs from "@/components/tabs/CalculatorTabs.vue";
import CalculatorTab from "@/components/tabs/CalculatorTab.vue";
import CalculatorSubmit from "@/components/CalculatorSubmit.vue";

export default {
  data() {
    return {
      calculator_store: useCalculatorStore(),
    };
  },
  mounted() {
    this.calculator_store.getCalculatorDataOnPageLoad();
  },
  methods: {},
  components: { CalculatorTabs, CalculatorTab, CalculatorSubmit },
};
</script>
