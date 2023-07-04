<template>
  <div class="calculator-tab">
    <CalculatorTabs />
    <CalculatorTab />
  </div>
  <CalculatorResult />
  <CalculatorControls />

  <!-- Генерация табицы для PDF. С таблицей работает services/DownloadPdfService -->
  <GenerateTablePdf />
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";

import CalculatorTabs from "@/components/tabs/CalculatorTabs.vue";
import CalculatorTab from "@/components/tabs/CalculatorTab.vue";
import CalculatorResult from "@/components/CalculatorResult.vue";
import CalculatorControls from "@/components/calculator-controls/CalculatorControls.vue";
import GenerateTablePdf from "@/components/GenerateTablePdf.vue";

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
    CalculatorControls,
    GenerateTablePdf,
  },
};
</script>
