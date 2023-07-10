<template>
  <div class="calculator-tab">
    <CalculatorTabs />
    <CalculatorTab />
  </div>
  <TableResult v-if="form_sent" />
  <CalculatorControls />

  <!-- Генерация табицы для PDF. С таблицей работает services/DownloadPdfService -->
  <GenerateTablePdf />
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";
import { useCallbackFormStore } from "@/stores/callback-form.js";

import CalculatorTabs from "@/components/calculator-tabs/Tabs.vue";
import CalculatorTab from "@/components/calculator-tabs/Tab.vue";
import TableResult from "@/components/calculator-result/TableResult.vue";
import CalculatorControls from "@/components/calculator-controls/Controls.vue";
import GenerateTablePdf from "@/components/GenerateTablePdf.vue";

export default {
  data() {
    return {
      calculator_store: useCalculatorStore(),
      callback_form_store: useCallbackFormStore(),
    };
  },
  mounted() {
    this.calculator_store.getCalculatorDataOnPageLoad();
  },
  components: {
    CalculatorTabs,
    CalculatorTab,
    TableResult,
    CalculatorControls,
    GenerateTablePdf,
  },
  computed: {
    form_sent() {
      return this.callback_form_store.sent.get();
    },
  },
};
</script>
