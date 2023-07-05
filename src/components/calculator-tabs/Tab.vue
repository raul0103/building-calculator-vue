<template>
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
            :key="calculator_store.calculator_key_active + '_' + option.key"
            class="calculator-tab__content-options__fields-item"
            @mouseenter.self="(e) => hoverOption(option.key, option.image)"
            @mouseleave.self="(e) => hoverOption(option.key, option.image)"
          >
            <div class="calculator-tab__content-options__fields-item-row">
              <div class="calculator-tab__content-options__fields-item-title">
                {{ `${option.title}, ${option.unit}` }}
              </div>
              <div class="calculator-tab__content-options__fields-item-choice">
                <!-- Если есть список значений option.values тогда выводится select -->

                <SelectTab
                  v-if="option.values"
                  :name="option.key"
                  :values="option.values"
                  :value="option.default"
                  :unit="option.unit"
                  :calculator_key_active="
                    calculator_store.calculator_key_active
                  "
                  :form_name="calculator_store.calculator_form_name"
                />

                <InputTab
                  v-else
                  :name="option.key"
                  :value="option.default"
                  :required="option.required"
                  :step="option.step"
                  :placeholder="option.placeholder"
                  :calculator_key_active="
                    calculator_store.calculator_key_active
                  "
                  :form_name="calculator_store.calculator_form_name"
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
        <div
          class="calculator-tab__content-options__fields"
          :class="{
            'block-disabled': fields_store?.checkEmptyRequiredFields(
              calculator_store.calculator_form_name
            ),
          }"
        >
          <li
            v-for="option in calculator_store.calculator_data_active?.config
              .additionally.options"
            :key="calculator_store.calculator_key_active + '_' + option.key"
            class="calculator-tab__content-options__fields-item"
            @mouseenter.self="(e) => hoverOption(option.key, option.image)"
            @mouseleave.self="(e) => hoverOption(option.key, option.image)"
          >
            <div class="calculator-tab__content-options__fields-item-row">
              <div class="calculator-tab__content-options__fields-item-title">
                {{ option.title }}
              </div>
              <div
                class="calculator-tab__content-options__fields-item-choice-checkbox"
              >
                <CheckboxTab
                  :name="option.key"
                  :active_image="option.image"
                  :calculator_key_active="
                    calculator_store.calculator_key_active
                  "
                  :form_name="calculator_store.calculator_form_name"
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
        </div>
      </div>

      <div class="calculator-tab__content-options__image">
        <TabSecondaryImage :hover_image="hovered.hover_image" />
        <TabMainImage :hover_image="hovered.hover_image" />
      </div>
    </div>
  </div>
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";
import { useFieldsStore } from "@/stores/fields.js";
import InputTab from "./components/ui/InputTab.vue";
import SelectTab from "./components/ui/SelectTab.vue";
import CheckboxTab from "./components/ui/CheckboxTab.vue";
import TabSecondaryImage from "./components/images/TabSecondaryImage.vue";
import TabMainImage from "./components/images/TabMainImage.vue";

export default {
  data() {
    return {
      calculator_store: useCalculatorStore(),
      fields_store: useFieldsStore(),
      hovered: {
        option_keys: {},
        hover_image: null,
      },
    };
  },
  mounted() {},
  methods: {
    hoverOption(key, hover_image) {
      /**
       * Предварительно удаляем hover у всех элементов
       * Просто при быстрых движениях мыши может наложиться два ховера
       */
      Object.keys(this.hovered.option_keys).forEach((object_key) => {
        if (object_key !== key) this.hovered.option_keys[object_key] = false;
      });

      if (!this.hovered.option_keys[key]) {
        this.hovered.option_keys[key] = true;
      } else {
        this.hovered.option_keys[key] = !this.hovered.option_keys[key];
      }

      /** сохраняем активный ключ для передачи в компонент с картинками */
      if (this.hovered.option_keys[key]) {
        this.hovered.hover_image = hover_image;
      } else {
        this.hovered.hover_image = null;
      }
    },
  },
  components: {
    InputTab,
    SelectTab,
    CheckboxTab,
    TabSecondaryImage,
    TabMainImage,
  },
};
</script>
