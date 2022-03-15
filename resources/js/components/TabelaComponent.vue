<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th v-for="(col, index) in colunas" :key="index" scope="col">
          {{ col.titulo }}
        </th>
        <th v-if="visualizar.visivel || editar.visivel || excluir.visivel">
          Ações
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, index) in dadosFiltrados" :key="index">
        <td v-for="(c, index) in colunas" :key="index">
          <span v-if="c.tipo == 'texto'">{{ item[c.col] }}</span>
          <span v-if="c.tipo == 'imagem'">
            <img
              style="width: 30px; height: 30px"
              :src="'storage/' + item[c.col]"
          /></span>
          <span v-if="c.tipo == 'data'">{{ item[c.col] }}</span>
        </td>
        <td v-if="visualizar.visivel || editar.visivel || excluir.visivel">
          <button
            type="button"
            class="btn btn-outline-info"
            :data-bs-toggle="visualizar.dataBsToggle"
            :data-bs-target="visualizar.dataBsTarget"
            @click="setItem(item)"
          >
            Visualizar
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary"
            :data-bs-toggle="editar.dataBsToggle"
            :data-bs-target="editar.dataBsTarget"
            @click="setItem(item)"
          >
            Editar
          </button>
          <button
            type="button"
            class="btn btn-outline-danger"
            :data-bs-toggle="excluir.dataBsToggle"
            :data-bs-target="excluir.dataBsTarget"
            @click="setItem(item)"
          >
            Excluir
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["colunas", "dados", "visualizar", "editar", "excluir"],
  computed: {
    dadosFiltrados() {
      let chaves = Object.keys(this.colunas);
      let filtradoDado = [];
      this.dados.forEach((item, index) => {
        let itemTemp = {};
        chaves.map((c, i) => {
          itemTemp[c] = item[c];
        });

        filtradoDado.push(itemTemp);
      });
      return filtradoDado;
    },
  },
  methods: {
    setItem(i) {
      this.$store.state.mostrarAlert = false;
      this.$store.state.item = i;
    },
  },
};
</script>

<style>
</style>
