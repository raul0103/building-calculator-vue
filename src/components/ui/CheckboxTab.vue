<template>
  <input
    type="checkbox"
    :name="name"
    @change="checkedValue"
    v-model="checked"
  />
</template>

<script>
import { useFieldsStore } from "@/stores/fields.js";

export default {
  props: {
    name: { default: "checkbox-name" },
    calculator_key_active: { default: null },
    /**
     * Название формы где находится поле.
     * Используется для записи не заполненных обязательных полей в stores/fields
     */
    form_name: { default: "form" },
  },
  data() {
    return {
      checked: false,
    };
  },
  beforeCreate() {
    this.fields_store = useFieldsStore();
  },
  beforeMount() {
    this.checked = this.setCompeledValue();
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
          this.checked,
          this.calculator_key_active
        );
        return this.value;
      }
    },
    checkedValue() {
      this.fields_store.setCompeledFields(
        this.form_name,
        this.name,
        this.checked,
        this.calculator_key_active
      );
    },
  },
};
</script>
