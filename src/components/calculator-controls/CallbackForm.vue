<template>
  <form @submit.prevent="submit" class="calculator-controls__form-callback">
    <div class="calculator-controls__form-callback__form-groups">
      <div
        v-for="field in callback_fields"
        :key="field.name"
        class="calculator-controls__form-callback__form-group"
      >
        <label>{{ field.label }}</label>
        <input
          v-model="field.value"
          :type="field.type"
          :name="field.name"
          :ref="field.ref"
          @input="validCallbackFields(field)"
          :class="{ 'input-error': field.errors?.length }"
        />
        <span
          class="calculator-controls__form-callback__form-group-error"
          v-for="error in field.errors"
          :key="error"
          >{{ error }}</span
        >
      </div>
    </div>
    <ButtonPrimary :pulse="true">Получить подробную смету</ButtonPrimary>
  </form>

  <ModalWindow :is_open="modal.is_open" @close="modal.is_open = false">
    <template v-slot:header>{{ modal.header }}</template>
    <template v-slot:content>{{ modal.content }}</template>
  </ModalWindow>
</template>

<script>
import axios from "axios";
import Inputmask from "inputmask";

import ModalWindow from "../ModalWindow.vue";
import ButtonPrimary from "@/components/ui/buttons/primary";
import CalculatorService from "@/services/CalculatorService.js";
import DownloadPdfService from "@/services/DownloadPdfService.js";
import { useVariablesStore } from "@/stores/variables.js";
import { useCalculatorStore } from "@/stores/calculator.js";
import { useCallbackFormStore } from "@/stores/callback-form.js";

export default {
  components: { ButtonPrimary, ModalWindow },
  data() {
    return {
      modal: {
        is_open: false,
        header: null,
        content: null,
      },
      calculator_store: useCalculatorStore(),
      variables_store: useVariablesStore(),
      callback_form_store: useCallbackFormStore(),
      calculator_service: new CalculatorService(),
      download_pdf_service: new DownloadPdfService(),
      callback_fields: [
        {
          label: "E-mail",
          name: "email",
          value: null,
          type: "email",
        },
        {
          label: "Телефон",
          name: "phone",
          value: null,
          type: "text",
          required: true,
          ref: "phone",
        },
        {
          label: "Имя",
          name: "name",
          value: null,
          type: "text",
        },
        {
          label: "Адрес объекта",
          name: "address",
          value: null,
          type: "text",
        },
      ],
    };
  },
  mounted() {
    let mask_phone = new Inputmask({ mask: "+7 (999)-999-99-9{2,3}" });
    mask_phone.mask(this.$refs.phone);
  },
  methods: {
    async submit() {
      // Проверяем на заполненность поля формы звонка
      if (this.validCallbackFields()) {
        this.sendMessage(); // Отправка сообщения менеджеру
      }
    },

    async sendMessage() {
      const fields = this.callback_fields.map((field) => ({
        name: field.name,
        value: field.value,
        valid: field.valid,
      }));

      /**
       * Сохраняем данные пользователя в хранилище.
       * Делаем это перед генераций сметы, так как при генерации будут использоваться поля имя, номер польщователя
       */
      this.callback_form_store.data.set(
        this.callback_fields.reduce((acc, field) => {
          acc[field.name] = field.value;
          return acc;
        }, {})
      );

      // Расчет данных
      await this.calculator_service.calculate();

      // Создаем PDF сметы и имя файла передает со всеми полями менеджеру
      const download_pdf = false;
      const smeta_pdf_filename = await this.download_pdf_service.generatePDF(
        download_pdf
      );
      fields.push({
        name: "pdf",
        value: location.origin + "/calculator/pdf/" + smeta_pdf_filename,
        valid: true,
      });

      let form_data = new FormData();
      form_data.append("fields", JSON.stringify(fields));

      // Отправили сообщение менеджеру
      axios
        .post(
          `${this.variables_store.api_url}/calculator/api/message/send-manager.php`,
          form_data
        )
        .then((response) => {
          //Если сообщение не отправлено, удаляем данные из хранилища
          if (response.data.status) {
            this.modal.header = "Форма отправлена";
            this.callback_form_store.sent.set(true);
          } else {
            this.callback_form_store.data.remove();
            this.modal.header = "Ошибка отправки формы";
          }

          this.modal.is_open = true;
          this.modal.content = response.data.message;
        })
        .catch(() => {
          this.callback_form_store.data.remove();
        });
    },
    validCallbackFields(field = null) {
      let form_valid = true;

      const checkValid = (field) => {
        if (field.required && (!field.value || !field.value.length)) {
          field.errors = ["Заполните обязательное поле"];
          field.valid = false;
          form_valid = false;
        } else {
          field.errors = [];
          field.valid = true;
        }
      };

      // Если было передано поле тогда проверить только его
      if (field) {
        checkValid(field);
      } else {
        this.callback_fields.forEach((field) => {
          checkValid(field);
        });
      }

      return form_valid;
    },
  },
};
</script>
