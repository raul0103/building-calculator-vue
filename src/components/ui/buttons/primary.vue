<template>
  <button
    :class="[
      'calculator-button-primary',
      {
        'pulse-button': pulse,
      },
    ]"
  >
    <slot></slot>
  </button>
</template>

<script>
export default {
  props: {
    pulse: { default: false },
  },
};
</script>
