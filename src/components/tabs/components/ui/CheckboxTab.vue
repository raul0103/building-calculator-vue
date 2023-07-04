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
import { useImagesStore } from "@/stores/images.js";
import CalculatorService from "@/services/CalculatorService.js";

export default {
  props: {
    name: { default: "checkbox-name" },
    calculator_key_active: { default: null },
    active_image: { default: null }, // Активная картинка.
    /**
     * Название формы где находится поле.
     * Используется для записи не заполненных обязательных полей в stores/fields
     */
    form_name: { default: "form" },
  },
  data() {
    return {
      checked: false,
      images_store: useImagesStore(),
      calculator_service: new CalculatorService(),
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

      /** Записали изображение как активное для отображения */
      if (this.active_image) {
        if (this.checked)
          this.images_store.setActiveImage(this.name, this.active_image);
        else this.images_store.removeActiveImage(this.name);
      }

      // Отправили данные для расчета, с проверкой отправлена ли была обратная форма
      const check_sent_callback = true;
      this.calculator_service.calculate(check_sent_callback);
    },
  },
};
</script>
