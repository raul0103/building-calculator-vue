<template>
  <div class="calculator-tab__content-options__image-secondary">
    <!-- Картинка при наведении -->
    <img
      class="hover-image"
      v-if="hover_image"
      :src="getHoverImage()"
      :style="hover_image.style"
    />
    <!-- Активные картинки -->
    <img
      :src="`${variables_store.api_url}/${image.src}`"
      :class="{ 'image-hide': hover_image }"
      :style="image.style"
      v-for="image in Object.values(images_store.getActiveImages())"
      :key="image"
    />
  </div>
</template>

<script>
import { useVariablesStore } from "@/stores/variables.js";
import { useImagesStore } from "@/stores/images.js";

export default {
  props: {
    hover_image: { default: null },
  },
  data() {
    return {
      variables_store: useVariablesStore(),
      images_store: useImagesStore(),
    };
  },

  methods: {
    getHoverImage() {
      return `${this.variables_store.api_url}/${this.hover_image.src}`;
    },
  },
};
</script>
