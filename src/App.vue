<template>
  <div class="calculator-tab">
    <CalculatorTabs />
    <CalculatorTab />
  </div>
  <CalculatorResult />
  <CalculatorSubmit />
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";

import CalculatorTabs from "@/components/tabs/CalculatorTabs.vue";
import CalculatorTab from "@/components/tabs/CalculatorTab.vue";
import CalculatorResult from "@/components/CalculatorResult.vue";
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
  components: {
    CalculatorTabs,
    CalculatorTab,
    CalculatorResult,
    CalculatorSubmit,
  },
};
</script>
