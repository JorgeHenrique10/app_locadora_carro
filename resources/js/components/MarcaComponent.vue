<template>
  <div>
    <card-component titulo="Pesquisar Marcas">
      <template v-slot:conteudo>
        <div class="row">
          <div class="col">
            <input-component
              texto_label="Id"
              texto_help="Opicional, campo para filtrar a pesquisa."
            >
              <input type="number" class="form-control" id="inputId" />
            </input-component>
          </div>
          <div class="col">
            <input-component
              texto_label="Nome da marca"
              texto_help="Opicional, campo para filtrar a pesquisa."
            >
              <input type="text" class="form-control" id="inputMarca" />
            </input-component>
          </div>
        </div>
      </template>
      <template v-slot:rodape>
        <div class="float-end">
          <button type="submit" class="btn btn-primary btn-sm">
            Pesquisar
          </button>
        </div>
      </template>
    </card-component>

    <card-component titulo="Listagem de Marcas">
      <template v-slot:conteudo>
        <tabela-component></tabela-component>
      </template>
      <template v-slot:rodape>
        <div class="float-end">
          <button
            type="submit"
            class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#addMarcaModal"
          >
            Adicionar
          </button>
        </div>
      </template>
    </card-component>

    <modal-component titulo="Cadastrar Marcas" id_modal="addMarcaModal">
      <template v-slot:alert v-if="mostrarAlert">
        <alert-component
          :titulo="tituloAlert"
          :tipo="tipoAlert"
          :mensagens_validacoes="errorsAlertValidacao"
          :mensagem="mensagemAlert"
        >
        </alert-component>
      </template>

      <template v-slot:conteudo>
        <div class="form-group">
          <input-component
            texto_label="Nome da marca"
            texto_help="Favor informar o nome da marca."
          >
            <input
              type="text"
              class="form-control"
              id="InputMarcaNovo"
              placeholder="Marca"
              v-model="nomeMarca"
            />
          </input-component>
        </div>
        <div class="form-group">
          <input-component
            texto_label="Imagem da marca"
            texto_help="Favor inserir a imagem da marca."
          >
            <input
              type="file"
              class="form-control"
              id="inputImagemNovo"
              @change="carregarImagem($event)"
            />
          </input-component>
        </div>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="salvar()">
          Salvar
        </button>
      </template>
    </modal-component>
  </div>
</template>

<script>
export default {
  data() {
    return {
      nomeMarca: "",
      imagemMarca: [],
      tipoAlert: "",
      tituloAlert: "",
      mensagemAlert: "",
      errorsAlertValidacao: [],
      mostrarAlert: false,
    };
  },
  methods: {
    salvar() {
      //   console.log(this.nomeMarca, this.imagemMarca);
      let data = new FormData();
      data.append("nome", this.nomeMarca);
      data.append("imagem", this.imagemMarca[0]);

      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
        },
      };

      axios
        .post("http://127.0.0.1:8000/api/marca", data, config)
        .then((response) => {
          this.mostrarAlert = true;
          this.tituloAlert = "Marca cadastrada com sucesso.";
          this.tipoAlert = "success";
          this.mensagemAlert =
            "O Id da Marca cadastrada  é: " + response.data.id;

          console.log(response.data.id);
        })
        .catch((errors) => {
          this.mostrarAlert = true;
          this.tituloAlert = "Erro ao efetuar o cadastro da marca.";
          this.tipoAlert = "error";
          this.errorsAlertValidacao = errors.response.data.errors;
          this.mensagemAlert = errors.response.data.message;
          console.log(errors.response.data.message);
        });
    },
    carregarImagem(e) {
      this.imagemMarca = e.target.files;
    },
  },
};
</script>

<style>
</style>
