<template>
  <form
    v-if="!is_sent"
    @submit.prevent="submit"
    class="calculator-controls__form-callback"
  >
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
    <button class="calculator-button-primary">Получить подробную смету</button>
  </form>
</template>

<script>
import axios from "axios";
import Inputmask from "inputmask";

import CalculatorService from "@/services/CalculatorService.js";
import LocalStorageService from "@/services/LocalStorageService.js";
import DownloadPdfService from "@/services/DownloadPdfService.js";
import { useVariablesStore } from "@/stores/variables.js";

export default {
  data() {
    return {
      calculator_service: new CalculatorService(),
      variables_store: useVariablesStore(),
      local_storage_service: new LocalStorageService(),
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
      is_sent: false, // Форма не отправлена
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
        await this.calculator_service.calculate(); // Расчет данных
        this.sendMessage(); // Отправка сообщения менеджеру
      }
    },

    async sendMessage() {
      const fields = this.callback_fields.map((field) => ({
        name: field.label,
        value: field.value,
        valid: field.valid,
      }));

      // Создаем PDF сметы и имя файла передает со всеми полями менеджеру
      const download_pdf = false;
      const smeta_pdf_filename = await this.download_pdf_service.generatePDF(
        download_pdf
      );
      fields.push({
        name: "Ссылка на смету",
        value: location.origin + "/calculator/pdf/" + smeta_pdf_filename,
        valid: true,
      });

      let form_data = new FormData();
      form_data.append("fields", JSON.stringify(fields));

      // Отправили сообщение менеджеру
      axios
        .post(
          `${this.variables_store.api_url}/calculator/api/send-message.php`,
          form_data
        )
        .then((response) => {
          //Если сообщение отправлено, сохраняем данные в хранилище для дальнейшего использования
          if (response.data.status) {
            this.local_storage_service.setStorage({
              callback: this.callback_fields.reduce((acc, field) => {
                acc[field.name] = field.value;
                return acc;
              }, {}),
            });
            this.is_sent = true;
          }
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
