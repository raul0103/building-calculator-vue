<template>
  <div class="calculator-tab__content-options__image-main">
    <img
      :class="{ 'image-hide': hover_image }"
      :src="getMainSrc()"
      :style="getMainStyle()"
    />
  </div>
</template>

<script>
import { useCalculatorStore } from "@/stores/calculator.js";
import { useVariablesStore } from "@/stores/variables.js";
import { useFieldsStore } from "@/stores/fields.js";

export default {
  props: {
    hover_image: { default: null },
  },
  data() {
    return {
      calculator_store: useCalculatorStore(),
      variables_store: useVariablesStore(),
      fields_store: useFieldsStore(),
      main_image: null,
    };
  },

  methods: {
    getMainIndex() {
      let image_index = 0;

      /** Если ошибок нет в форме вывести второе изображение, иначе первое главное */
      if (
        !this.fields_store.checkEmptyRequiredFields(
          this.calculator_store.calculator_form_name
        )
      ) {
        image_index = 1;
      }

      return image_index;
    },
    getMainSrc() {
      const image_index = this.getMainIndex();
      return `${this.variables_store.api_url}/${this.calculator_store.calculator_data_active?.image.src[image_index]}`;
    },
    getMainStyle() {
      const image_index = this.getMainIndex();

      return `${this.calculator_store.calculator_data_active?.image.style[image_index]}`;
    },
  },
};
</script>
