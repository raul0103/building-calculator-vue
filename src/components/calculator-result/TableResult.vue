<template>
  <div class="calculator-result">
    <Transition>
      <div v-if="results">
        <div class="calculator-result__board">
          Общая стоимость:
          <span>{{ results.total }}</span> ₽
        </div>

        <div class="calculator-result__title">Смета</div>
        <div class="calculator-result__table-container">
          <table class="calculator-result__table">
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
              class="calculator-result__table__tbody-options"
              v-for="(expenses, expenses_key) in results.additional_expenses"
              :key="expenses_key"
            >
              <tr class="calculator-result__table__tbody-options-title">
                <td colspan="5">{{ expenses.title }}</td>
              </tr>
              <tr
                v-for="(options, options_key) in expenses.options"
                :key="options_key"
              >
                <td>{{ options.title }}</td>
                <td v-html="options.unit"></td>
                <td>{{ options.quantity }}</td>
                <td class="calculator-result__table__tbody-options-price">
                  {{ options.price }} ₽
                </td>
                <td class="calculator-result__table__tbody-options-price">
                  {{ options.cost }} ₽
                </td>
              </tr>
              <tr class="calculator-result__table__total-cost">
                <td colspan="4">
                  <!-- <small>({{ expenses.title }})</small> -->
                  Итого
                </td>
                <td>{{ expenses.total_cost }} ₽</td>
              </tr>
            </tbody>
            <tr class="calculator-result__table__total-cost">
              <td colspan="4">
                Общая стоимость работ с учетом материалов, руб.
              </td>
              <td>{{ results.total }} ₽</td>
            </tr>
          </table>
        </div>
      </div>
    </Transition>

    <!--  Показываем уведомление только если пользователь отправил форму и нет результатов -->
    <Transition>
      <div v-if="!results" class="calculator-result__board">
        Заполните обязательные поля
      </div>
    </Transition>
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
