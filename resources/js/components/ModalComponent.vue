<template>
  <div
    class="modal fade"
    :id="id_modal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ titulo }}</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <slot name="alert"> </slot>
          <slot name="conteudo"> </slot>
        </div>
        <div class="modal-footer">
          <slot name="rodape"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["id_modal", "titulo"],
};
</script>

<style>
</style>
