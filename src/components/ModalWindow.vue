<template>
  <Transition>
    <div v-if="_is_open" class="calculator-modal">
      <div class="calculator-modal__background" @click="close"></div>
      <div class="calculator-modal__content">
        <h2><slot name="header"></slot></h2>
        <div>
          <slot name="content"></slot>
        </div>
        <div class="calculator-modal__footer">
          <slot name="footer-button"></slot>
          <button
            class="calculator-modal__button modal-btn-close pulse-button"
            @click="close"
          >
            Закрыть
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
export default {
  props: {
    is_open: { default: false },
  },
  computed: {
    _is_open() {
      return this.is_open;
    },
  },
  methods: {
    close() {
      this._is_open = false;
      this.$emit("close");
    },
  },
};
</script>
