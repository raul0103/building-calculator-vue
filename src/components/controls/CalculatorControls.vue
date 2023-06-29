<template>
  <div
    class="calculator-controls"
    :class="{
      'block-disabled': fields_store?.checkEmptyRequiredFields(
        calculator_store.calculator_form_name
      ),
    }"
  >
    <CalculatorSubmit />
    <DownloadPdf :results="results_store.getResults()" />
  </div>
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";
import { useFieldsStore } from "@/stores/fields.js";
import { useResultsStore } from "@/stores/results.js";
import DownloadPdf from "./components/DownloadPdf.vue";
import CalculatorSubmit from "./components/CalculatorSubmit.vue";

export default {
  components: { DownloadPdf, CalculatorSubmit },
  data() {
    return {
      calculator_store: useCalculatorStore(),
      fields_store: useFieldsStore(),
      results_store: useResultsStore(),
    };
  },
};
</script>
