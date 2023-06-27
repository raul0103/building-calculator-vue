/**
 * Сохраняет активные картинки для отрисовки в компонентах
 */

import { ref } from "vue";
import { defineStore } from "pinia";

export const useImagesStore = defineStore("images", () => {
  /**
   * Объект {field_name:{image}}
   */
  const active_images = ref({});

  function setActiveImage(field_name, image_src) {
    active_images.value[field_name] = image_src;
  }

  function removeActiveImage(field_name) {
    delete active_images.value[field_name];
  }

  return {
    active_images,
    setActiveImage,
    removeActiveImage,
  };
});
