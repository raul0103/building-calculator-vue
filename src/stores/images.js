/**
 * Сохраняет активные картинки для отрисовки в компонентах
 * Сохраняет по активному ключу калькулятора. что бы при переключении на другой кальк старые изображения не высвечивались
 */

import { ref } from "vue";
import { defineStore } from "pinia";
import { useCalculatorStore } from "@/stores/calculator";

export const useImagesStore = defineStore("images", () => {
  /**
   * Объект {field_name:{image}}
   */
  const active_images = ref({});
  const calculator_store = useCalculatorStore();

  function setActiveImage(field_name, image_src) {
    if (!active_images.value[calculator_store.calculator_key_active]) {
      active_images.value[calculator_store.calculator_key_active] = {};
    }

    active_images.value[calculator_store.calculator_key_active][field_name] =
      image_src;
  }

  function removeActiveImage(field_name) {
    delete active_images.value[calculator_store.calculator_key_active][
      field_name
    ];
  }

  function getActiveImages() {
    if (active_images.value[calculator_store.calculator_key_active]) {
      return active_images.value[calculator_store.calculator_key_active];
    } else {
      return {};
    }
  }

  return {
    active_images,
    setActiveImage,
    removeActiveImage,
    getActiveImages,
  };
});
