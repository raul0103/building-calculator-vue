<template>
  <select :name="name" @change="selectValue">
    <option
      v-for="option_value in values"
      :value="option_value"
      :key="option_value"
      :selected="option_value == _value"
    >
      {{ `${option_value} ${unit}` }}
    </option>
  </select>
</template>

<script>
import { useFieldsStore } from "@/stores/fields.js";

export default {
  props: {
    name: { default: "select-name" },
    values: { default: [] },
    value: { default: null },
    unit: { default: null },
    calculator_key_active: { default: null },
    /**
     * Название формы где находится поле.
     * Используется для записи не заполненных обязательных полей в stores/fields
     */
    form_name: { default: "form" },
  },
  beforeCreate() {
    this.fields_store = useFieldsStore();
  },
  beforeMount() {
    this._value = this.setCompeledValue();
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
    selectValue(e) {
      this.fields_store.setCompeledFields(
        this.form_name,
        this.name,
        e.target.value,
        this.calculator_key_active
      );
    },
  },
};
</script>
