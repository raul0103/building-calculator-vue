<template>
  <div class="calculator-tab">
    <CalculatorTabs />
    <CalculatorTab />
  </div>
  <TableResult />
  <CalculatorControls />

  <!-- Генерация табицы для PDF. С таблицей работает services/DownloadPdfService -->
  <GenerateTablePdf />
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";

import CalculatorTabs from "@/components/calculator-tabs/Tabs.vue";
import CalculatorTab from "@/components/calculator-tabs/Tab.vue";
import TableResult from "@/components/calculator-result/TableResult.vue";
import CalculatorControls from "@/components/calculator-controls/Controls.vue";
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
    TableResult,
    CalculatorControls,
    GenerateTablePdf,
  },
};
</script>
