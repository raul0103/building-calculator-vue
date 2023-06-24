import { ref } from "vue";
import { defineStore } from "pinia";

export const useFieldsStore = defineStore("fields", () => {
  /**
   * Не заполненные обязательные поля
   * {form_name:{field_name:is_error}} / {back_call:{phone:true}} - Ошибка в поле с телефоном
   */
  const empty_required_fields = ref({});

  /**
   * Записали обязательные поля и статус ошибки
   *
   * @param form_name - Название формы в которой находится поле
   * @param field_name - Название поля
   * @param is_error - true/false - имеет ли поле ошибку заполнения
   */
  function setEmptyRequiredFields(form_name, field_name, is_error) {
    if (!empty_required_fields.value[form_name]) {
      empty_required_fields.value[form_name] = {};
    }

    empty_required_fields.value[form_name][field_name] = is_error;
  }

  /**
   * Проверяем есть ли с ошибкой обязательные поля в указанной форме
   *
   * @param form_name - Название формы
   * @return boolean
   */
  function checkEmptyRequiredFields(form_name) {
    if (empty_required_fields.value[form_name])
      return (
        Object.values(empty_required_fields.value[form_name]).indexOf(true) > -1
      );
  }

  return {
    setEmptyRequiredFields,
    checkEmptyRequiredFields,
  };
});
