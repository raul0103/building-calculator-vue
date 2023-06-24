<template>
  <input
    :type="type"
    :step="step"
    :min="min"
    :name="name"
    :placeholder="placeholder"
    v-model="_value"
    :class="{ 'input-error': required_error }"
    @input="inputValue"
  />
</template>

<script>
import { useFieldsStore } from "@/stores/fields.js";

export default {
  props: {
    type: { default: "number" },
    step: { default: 0.1 },
    min: { default: 0 },
    name: { default: "input-name" },
    value: { default: null },
    required: { default: false },
    placeholder: { default: null },
    /**
     * Название формы где находится поле.
     * Используется для записи не заполненных обязательных полей в stores/fields
     */
    form_name: { default: "form" },
  },
  data() {
    return {
      _value: this.value,
      required_error: this.checkRequiredError(this.value),
    };
  },
  methods: {
    /** Если поле обязательно и не заполненно - считать ошибкой */
    checkRequiredError(input_value) {
      let output = false;
      if (this.required && (!input_value || input_value == 0)) output = true;

      this.setEmptyRequiredFields(output);

      return output;
    },
    inputValue(e) {
      this.required_error = this.checkRequiredError(e.target.value);
    },
    setEmptyRequiredFields(error_boolean) {
      const fields_store = useFieldsStore();
      fields_store.setEmptyRequiredFields(
        this.form_name,
        this.name,
        error_boolean
      );
    },
  },
};
</script>
