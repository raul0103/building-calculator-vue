<template>
  <div style="display: none" id="calculator-table-pdf" v-if="results">
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
</template>
<script>
import { useResultsStore } from "@/stores/results.js";

export default {
  data() {
    return {
      results_store: useResultsStore(),
    };
  },
  computed: {
    results() {
      return this.results_store.getResults();
    },
  },
};
</script>
