<template>
  <div class="container mb-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ titulo }}</div>

          <div class="card-body">
            <slot name="conteudo"> </slot>
          </div>
          <div class="card-footer">
            <slot name="rodape"> </slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["titulo"],
};
</script>

<style>
</style>
