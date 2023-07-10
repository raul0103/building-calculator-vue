<template>
  <div style="display: none" id="calculator-table-pdf" v-if="results">
    <table style="width: 100%">
      <thead>
        <tr>
          <td colspan="2">
            <h1 style="text-align: center; width: 100%; font-size: 2em">
              Строительство фундаментов под ключ
            </h1>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center">
            {{ smeta_header_contacts }}
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Адрес объекта:</td>
          <td>{{ callback_form_data?.address }}</td>
        </tr>
        <tr>
          <td>Заказчик:</td>
          <td>{{ callback_form_data?.name }}</td>
        </tr>
        <tr>
          <td>Телефон:</td>
          <td>{{ callback_form_data?.phone }}</td>
        </tr>
        <tr>
          <td>E-mail:</td>
          <td>{{ callback_form_data?.email }}</td>
        </tr>
        <tr v-for="(value, name) in getCalculatorFields()" :key="name">
          <td>{{ name }}</td>
          <td>{{ value }}</td>
        </tr>
      </tbody>
    </table>

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
    <div style="width: 100%; mergin-top: 20px">{{ smeta_footer_contacts }}</div>
  </div>
</template>
<script>
import { useResultsStore } from "@/stores/results.js";
import { useFieldsStore } from "@/stores/fields.js";
import { useCalculatorStore } from "@/stores/calculator";
import { useCallbackFormStore } from "@/stores/callback-form.js";

export default {
  data() {
    return {
      results_store: useResultsStore(),
      fields_store: useFieldsStore(),
      calculator_store: useCalculatorStore(),
      callback_form_store: useCallbackFormStore(),
      smeta_header_contacts: window.SMETA_HEADER_CONTACTS,
      smeta_footer_contacts: window.SMETA_FOOTER_CONTACTS,
    };
  },
  computed: {
    results() {
      return this.results_store.getResults();
    },
    callback_form_data() {
      return this.callback_form_store.data.get();
    },
  },
  methods: {
    getCalculatorFields() {
      const calculator_type = {
        key: this.calculator_store.calculator_key_active,
        title: this.calculator_store.calculator_data_active["title"],
      };
      const fields_complite =
        this.fields_store.completed_fields[
          this.calculator_store.calculator_form_name
        ][calculator_type.key];

      let result = {
        Тип: calculator_type.title,
      };

      Object.keys(fields_complite).forEach((key) => {
        this.calculator_store.calculator_data_active["config"]["dimensions"][
          "options"
        ].forEach((option) => {
          if (option.key == key && fields_complite[key])
            result[option.title] = fields_complite[key] + " " + option.unit;
        });
      });

      return result;
    },
  },
};
</script>
