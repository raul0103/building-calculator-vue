/**
 * Стор для хранения полей с ошибками и данных по заполненным полям
 */

import { ref } from "vue";
import { defineStore } from "pinia";

export const useFieldsStore = defineStore("fields", () => {
  /**
   * Не заполненные обязательные поля
   * {form_name:{field_name:is_error}} / {back_call:{phone:true}} - Ошибка в поле с телефоном
   */
  const empty_required_fields = ref({});
  /**
   * Заполненные поля
   * {form_name:{field_name:value}}
   */
  const completed_fields = ref({});

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

  /**
   * Записываем заполненные поля формы
   *
   * @param form_name
   * @param field_name
   * @param value
   * @param calculator_key_active - Ключ активного калькулятора. В дальнейшем получаем данные по активному калькулятору
   */
  function setCompeledFields(
    form_name,
    field_name,
    value,
    calculator_key_active
  ) {
    if (!completed_fields.value[form_name]) {
      completed_fields.value[form_name] = {};
    }
    if (!completed_fields.value[form_name][calculator_key_active]) {
      completed_fields.value[form_name][calculator_key_active] = {};
    }
    completed_fields.value[form_name][calculator_key_active][field_name] =
      value;
  }

  /**
   * Получает ранее записанные данные по названию формы, полю, и активному калькулятору
   *
   * @param form_name
   * @param field_name
   * @param calculator_key_active
   */
  function getCompeledField(form_name, field_name, calculator_key_active) {
    try {
      return completed_fields.value[form_name][calculator_key_active][
        field_name
      ];
    } catch {
      return false;
    }
  }

  return {
    completed_fields,
    setEmptyRequiredFields,
    checkEmptyRequiredFields,
    setCompeledFields,
    getCompeledField,
  };
});
