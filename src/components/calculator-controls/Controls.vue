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
    <CallbackForm v-if="!form_sent" />

    <!-- Отображаем если Есть результат расчета и пользователь отправлял заявку -->
    <div v-if="results && form_sent">
      <div class="calculator-controls__buttons-row">
        <ButtonPrimary
          :pulse="true"
          @click="download_pdf_service.generatePDF()"
        >
          Скачать PDF
        </ButtonPrimary>
        <ButtonPrimary :pulse="true" @click="confirmEmail">
          Отправить на почту
        </ButtonPrimary>
      </div>

      <ModalWindow :is_open="modal_open" @close="modal_open = false">
        <template v-slot:header> E-mail </template>
        <template v-slot:content>
          <div class="calculator__form">
            <div class="calculator__form-group">
              <label>Подтвердите ваш адрес электронной почты</label>
              <input type="email" v-model="user_email" />
              <span class="calculator__form-group-error" v-if="error_email">{{
                error_email
              }}</span>
            </div>
          </div>
        </template>
        <template v-slot:footer-button>
          <button
            class="calculator-modal__button modal-btn-submit pulse-button"
            @click="submit"
          >
            Отправить
          </button>
        </template>
      </ModalWindow>
    </div>
  </div>
</template>

<script>
import utils from "@/utils/index.js";
import { useCalculatorStore } from "@/stores/calculator.js";
import { useFieldsStore } from "@/stores/fields.js";
import { useResultsStore } from "@/stores/results.js";
import { useCallbackFormStore } from "@/stores/callback-form.js";
import DownloadPdfService from "@/services/DownloadPdfService.js";
import ButtonPrimary from "@/components/ui/buttons/primary";
import CallbackForm from "./CallbackForm.vue";
import ModalWindow from "../ModalWindow.vue";

export default {
  components: { CallbackForm, ButtonPrimary, ModalWindow },
  data() {
    return {
      calculator_store: useCalculatorStore(),
      fields_store: useFieldsStore(),
      results_store: useResultsStore(),
      callback_form_store: useCallbackFormStore(),
      download_pdf_service: new DownloadPdfService(),
      modal_open: false,
      user_email: null,
      error_email: null,
    };
  },

  methods: {
    /** Модалка с подтверждением почты */
    confirmEmail() {
      const callback_data = this.callback_form_store.data.get();
      this.user_email = callback_data?.email;

      this.modal_open = true;
    },

    /** Отправка письма, + запись почты в хранилище*/
    submit() {
      if (this.checkEmail()) {
        const storage_data = this.callback_form_store.data.get();
        storage_data.email = this.user_email;
        this.callback_form_store.data.set(storage_data);

        this.download_pdf_service.sendPdfToUserEmail(this.user_email);
        this.modal_open = false;
      }
    },

    checkEmail() {
      if (!this.user_email) {
        this.error_email = "Почта не указана";
        return false;
      }
      if (!utils.validateEmail(this.user_email)) {
        this.error_email = "Почта указана не верно";
        return false;
      }

      this.error_email = null;
      return true;
    },
  },
  computed: {
    results() {
      return this.results_store.getResults();
    },
    form_sent() {
      return this.callback_form_store.sent.get();
    },
  },
};
</script>
