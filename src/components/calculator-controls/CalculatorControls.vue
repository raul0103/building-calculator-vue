<template>
  <div
    class="calculator-controls"
    :class="{
      'block-disabled': fields_store?.checkEmptyRequiredFields(
        calculator_store.calculator_form_name
      ),
    }"
  >
    <!-- Отображаем если пользователь еще не отправлял заявку -->
    <CallbackForm v-if="!local_storage_service.getStorage('callback')" />

    <button
      v-if="results_store.getResults()"
      @click="download_pdf_service.generatePDF()"
      class="calculator-button-primary"
    >
      Скачать PDF
    </button>
  </div>
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";
import { useFieldsStore } from "@/stores/fields.js";
import { useResultsStore } from "@/stores/results.js";
import LocalStorageService from "@/services/LocalStorageService.js";
import DownloadPdfService from "@/services/DownloadPdfService.js";
import CallbackForm from "./CallbackForm.vue";

export default {
  components: { CallbackForm },
  data() {
    return {
      calculator_store: useCalculatorStore(),
      fields_store: useFieldsStore(),
      results_store: useResultsStore(),
      download_pdf_service: new DownloadPdfService(),
      local_storage_service: new LocalStorageService(),
    };
  },
};
</script>
