<template>
  <div class="mb-3">
    <label class="form-label">{{ texto_label }}</label>
    <slot></slot>
    <div class="form-text">
      {{ texto_help }}
    </div>
  </div>
</template>

<script>
export default {
  props: ["texto_label", "texto_help"],
};
</script>

<style>
</style>
