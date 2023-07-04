<template>
  <div v-if="results">
    <button @click="generatePDF" class="calculator-button-primary">
      Скачать PDF
    </button>
    <div ref="table_import" style="display: none">
      <table>
        <thead>
          <tr>
            <th>Наименование</th>
            <th>Ед. изм</th>
            <th>Количество</th>
            <th>Цена, руб./ед. изм.</th>
            <th>Сумма, руб.</th>
          </tr>
        </thead>
        <tbody
          v-for="(expenses, expenses_key) in results.additional_expenses"
          :key="expenses_key"
        >
          <tr class="bold center">
            <td colspan="5">{{ expenses.title }}</td>
          </tr>
          <tr
            v-for="(options, options_key) in expenses.options"
            :key="options_key"
          >
            <td>{{ options.title }}</td>
            <td v-html="options.unit"></td>
            <td>{{ options.quantity }}</td>
            <td>{{ options.price }} ₽</td>
            <td>{{ options.cost }} ₽</td>
          </tr>
          <tr>
            <td colspan="4" class="bold">Итого</td>
            <td class="bold">{{ expenses.total_cost }} ₽</td>
          </tr>
        </tbody>
        <tr>
          <td colspan="4">Общая стоимость работ с учетом материалов, руб.</td>
          <td>{{ results.total }} ₽</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useVariablesStore } from "@/stores/variables.js";

export default {
  props: {
    results: { default: null },
  },
  data() {
    return {
      variables_store: useVariablesStore(),
      table_style: `
        table {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #000;
            border-collapse: collapse;
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table th {
            font-weight: bold;
            padding: 5px;
            background: #efefef;
            border: 1px solid #000;
        }

        table td {
            border: 1px solid #000;
            padding: 5px;
        }

        table .bold{
          font-weight: bold;
        }
        table .center{
          text-align: center
        }
      `,
    };
  },
  methods: {
    generatePDF() {
      let form_data = new FormData();

      form_data.append(
        "table_html",
        `<style>${this.table_style}</style>${this.$refs.table_import.innerHTML}`
      );

      axios
        .post(
          `${this.variables_store.api_url}/calculator/api/pdf/pdf.php`,
          form_data
        )
        .then((response) => {
          // Создаем ссылку для скачивания файла
          const download_link = document.createElement("a");
          download_link.href = `${this.variables_store.api_url}/calculator/api/pdf/download.php?filename=${response.data.filename}`;
          // Добавляем ссылку на страницу и автоматически кликаем по ней
          document.body.appendChild(download_link);
          download_link.click();
          document.body.removeChild(download_link);
        })
        .catch((error) => {
          console.error("Ошибка при генерации и сохранении PDF:", error);
        });
    },
  },
};
</script>
