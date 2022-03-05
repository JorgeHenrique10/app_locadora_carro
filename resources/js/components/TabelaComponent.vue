<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th v-for="(col, index) in colunas" :key="index" scope="col">
          {{ col.titulo }}
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
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["colunas", "dados"],
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
};
</script>

<style>
</style>
