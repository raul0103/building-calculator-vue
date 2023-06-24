<template>
  <!-- {{ calculator_store.calculator_data_active }} -->
  <!-- {{ fields_store?.checkEmptyRequiredFields(form_name) }} -->

  <div class="calculator-tab__content">
    <div class="calculator-tab__content-title">
      Расчёт стоимости ({{ calculator_store.calculator_data_active?.title }})
    </div>
    <div class="calculator-tab__content-options">
      <div class="calculator-tab__content-options__item">
        <div class="calculator-tab__content-options__item-title">
          {{ calculator_store.calculator_data_active?.config.dimensions.title }}
        </div>
        <ul class="calculator-tab__content-options__fields">
          <li
            v-for="option in calculator_store.calculator_data_active?.config
              .dimensions.options"
            :key="option.key"
            class="calculator-tab__content-options__fields-item"
          >
            <div class="calculator-tab__content-options__fields-item-row">
              <div class="calculator-tab__content-options__fields-item-title">
                {{ `${option.title}, ${option.unit}` }}
              </div>
              <div class="calculator-tab__content-options__fields-item-choice">
                <!-- Если есть список значений option.values тогда выводится select -->
                <select v-if="option.values" :name="option.key">
                  <option
                    v-for="option_value in option.values"
                    :value="option_value"
                    :key="option_value"
                    :selected="option_value == option.default"
                  >
                    {{ `${option_value} ${option.unit}` }}
                  </option>
                </select>

                <MyInput
                  v-else
                  :name="option.key"
                  :value="option.default"
                  :required="option.required"
                  :step="option.step"
                  :placeholder="option.placeholder"
                  :form_name="form_name"
                />
              </div>
            </div>
            <div
              v-if="option.description"
              class="calculator-tab__content-options__fields-item-description"
            >
              {{ option.description }}
            </div>
          </li>
        </ul>
      </div>

      <div class="calculator-tab__content-options__item">
        <div class="calculator-tab__content-options__item-title">
          {{
            calculator_store.calculator_data_active?.config.additionally.title
          }}
        </div>
        <div class="calculator-tab__content-options__fields"></div>
      </div>

      <div class="calculator-tab__content-options__image">IMAGE</div>
    </div>
  </div>
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";
import { useFieldsStore } from "@/stores/fields.js";
import MyInput from "@/components/ui/MyInput.vue";

export default {
  data() {
    return {
      /**
       * Название формы где находится поле.
       * Используется для записи не заполненных обязательных полей в stores/fields
       */
      form_name: "calculator",
      calculator_store: useCalculatorStore(),
      fields_store: useFieldsStore(),
    };
  },
  mounted() {},
  methods: {},
  components: { MyInput },
};
</script>
