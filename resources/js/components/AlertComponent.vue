<template>
  <div>
    <div :class="classe" role="alert">
      <h5>{{ titulo }}</h5>
      <span v-if="mensagem">
        {{ mensagem }}
      </span>
      <hr />
      <div>
        <ul>
          <li v-for="(error, index) in mensagens_validacoes" :key="index">
            {{ error[0] }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["titulo", "tipo", "mensagens_validacoes", "mensagem"],
  data() {
    return {};
  },
  computed: {
    classe() {
      if (this.tipo == "error") {
        return "alert alert-danger";
      } else {
        return "alert alert-success";
      }
    },
  },
};
</script>

<style>
</style>
