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
    calculator_key_active: { default: null },
    /**
     * Название формы где находится поле.
     * Используется для записи не заполненных обязательных полей в stores/fields
     */
    form_name: { default: "form" },
  },
  data() {
    return {
      /** Данные переменные инициализируются в хуке beforeMount*/
      _value: null,
      required_error: null,
    };
  },
  beforeCreate() {
    this.fields_store = useFieldsStore();
  },
  beforeMount() {
    this._value = this.setCompeledValue();
    this.required_error = this.checkRequiredError(this._value);
  },
  methods: {
    /**
     * Если ранее были записаны данные в стор тогда получить их
     * Иначе записать новые
     */
    setCompeledValue() {
      const compeled_field = this.fields_store.getCompeledField(
        this.form_name,
        this.name,
        this.calculator_key_active
      );
      if (compeled_field) {
        return compeled_field;
      } else {
        this.fields_store.setCompeledFields(
          this.form_name,
          this.name,
          this.value,
          this.calculator_key_active
        );
        return this.value;
      }
    },
    /** Если поле обязательно и не заполненно - считать ошибкой */
    checkRequiredError(input_value) {
      let output = false;
      if (this.required && (!input_value || input_value == 0)) output = true;

      this.setEmptyRequiredFields(output);

      return output;
    },
    inputValue(e) {
      this.required_error = this.checkRequiredError(e.target.value);
      this.fields_store.setCompeledFields(
        this.form_name,
        this.name,
        e.target.value,
        this.calculator_key_active
      );
    },
    setEmptyRequiredFields(is_error) {
      this.fields_store.setEmptyRequiredFields(
        this.form_name,
        this.name,
        is_error
      );
    },
  },
};
</script>
