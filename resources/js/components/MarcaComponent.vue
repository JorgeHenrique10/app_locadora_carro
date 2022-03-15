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
              <input
                type="number"
                class="form-control"
                id="inputId"
                v-model="busca.id"
              />
            </input-component>
          </div>
          <div class="col">
            <input-component
              texto_label="Nome da marca"
              texto_help="Opicional, campo para filtrar a pesquisa."
            >
              <input
                type="text"
                class="form-control"
                id="inputMarca"
                v-model="busca.nome"
              />
            </input-component>
          </div>
        </div>
      </template>
      <template v-slot:rodape>
        <div class="float-end">
          <button
            type="submit"
            class="btn btn-primary btn-sm"
            @click="pesquisar()"
          >
            Pesquisar
          </button>
        </div>
      </template>
    </card-component>

    <card-component titulo="Listagem de Marcas">
      <template v-slot:conteudo>
        <tabela-component
          :colunas="colunasTabela"
          :dados="listaMarcas"
          :visualizar="{
            visivel: true,
            dataBsToggle: 'modal',
            dataBsTarget: '#showMarcaModal',
          }"
          :editar="{
            visivel: true,
            dataBsToggle: 'modal',
            dataBsTarget: '#editarMarcaModal',
          }"
          :excluir="{
            visivel: true,
            dataBsToggle: 'modal',
            dataBsTarget: '#excluirMarcaModal',
          }"
        ></tabela-component>
      </template>
      <template v-slot:rodape>
        <div class="row">
          <paginacao-component>
            <li
              :class="item.active ? 'page-item active' : 'page-item'"
              v-for="(item, index) in paginacao"
              :key="index"
            >
              <a
                class="page-link"
                v-html="item.label"
                @click="paginar(item.url)"
              ></a>
            </li>
          </paginacao-component>

          <div class="col">
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
          </div>
        </div>
      </template>
    </card-component>

    <modal-component titulo="Cadastrar Marcas" id_modal="addMarcaModal">
      <template v-slot:alert v-if="$store.state.mostrarAlert">
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
        <button
          type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal"
          @click="fechar()"
        >
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="salvar()">
          Salvar
        </button>
      </template>
    </modal-component>

    <modal-component titulo="Visualizar Marca" id_modal="showMarcaModal">
      <template v-slot:alert v-if="$store.state.mostrarAlert">
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
          <input-component texto_label="Nome da marca">
            <input
              type="text"
              class="form-control"
              id="InputMarcaView"
              placeholder="Marca"
              :value="$store.state.item.nome"
              disabled
            />
          </input-component>
        </div>
        <div class="form-group">
          <p>Imagem da Marca</p>
          <img
            :src="'storage/' + $store.state.item.imagem"
            v-if="$store.state.item.imagem"
          />
        </div>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
      </template>
    </modal-component>

    <modal-component titulo="Editar Marca" id_modal="editarMarcaModal">
      <template v-slot:alert v-if="$store.state.mostrarAlert">
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
              v-model="$store.state.item.nome"
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
        <button
          type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal"
          @click="fechar()"
        >
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="editar()">
          Salvar
        </button>
      </template>
    </modal-component>

    <modal-component titulo="Excluir Marca" id_modal="excluirMarcaModal">
      <template v-slot:alert v-if="$store.state.mostrarAlert">
        <alert-component
          :titulo="tituloAlert"
          :tipo="tipoAlert"
          :mensagens_validacoes="errorsAlertValidacao"
          :mensagem="mensagemAlert"
        >
        </alert-component>
      </template>

      <template v-slot:conteudo>
        <p class="alert-danger">
          Tem certeza que deseja excluir este registro?
        </p>
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
              :value="$store.state.item.nome"
              disabled
            />
          </input-component>
        </div>
        <div class="form-group">
          <p>Imagem da Marca</p>
          <img
            :src="'storage/' + $store.state.item.imagem"
            v-if="$store.state.item.imagem"
          />
        </div>
      </template>
      <template v-slot:rodape>
        <button
          type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal"
          @click="fechar()"
        >
          Fechar
        </button>
        <button type="button" class="btn btn-secondary" @click="deletar()">
          Deletar
        </button>
      </template>
    </modal-component>
  </div>
</template>

<script>
export default {
  data() {
    return {
      urlBase: "http://127.0.0.1:8000/api/marca",
      urlPaginacao: "",
      paginacao: [],
      nomeMarca: "",
      imagemMarca: [],
      tipoAlert: "",
      tituloAlert: "",
      mensagemAlert: "",
      errorsAlertValidacao: [],
      colunasTabela: {
        id: { titulo: "ID", col: "id", tipo: "texto" },
        nome: { titulo: "Marca", col: "nome", tipo: "texto" },
        imagem: { titulo: "Imagem", col: "imagem", tipo: "imagem" },
      },
      listaMarcas: [],
      busca: { id: "", nome: "" },
      filtro: "",
    };
  },
  methods: {
    salvar() {
      let data = new FormData();
      data.append("nome", this.nomeMarca);
      data.append("imagem", this.imagemMarca[0]);

      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      axios
        .post(this.urlBase, data, config)
        .then((response) => {
          this.$store.state.mostrarAlert = true;
          this.tituloAlert = "Marca cadastrada com sucesso.";
          this.tipoAlert = "success";
          this.mensagemAlert =
            "O Id da Marca cadastrada  é: " + response.data.id;
          this.listarMarcas();
        })
        .catch((errors) => {
          this.$store.state.mostrarAlert = true;
          this.tituloAlert = "Erro ao efetuar o cadastro da marca.";
          this.tipoAlert = "error";
          this.errorsAlertValidacao = errors.response.data.errors;
          this.mensagemAlert = errors.response.data.message;
        });
    },
    editar() {
      let id = this.$store.state.item.id;
      let formData = new FormData();
      formData.append("_method", "patch");
      formData.append("nome", this.$store.state.item.nome);

      if (this.imagemMarca.length != 0) {
        let img = this.imagemMarca[0];
        formData.append("imagem", img);
        this.imagemMarca = [];
      }
      let config = {
        "Content-Type": "multipart/form-data",
      };

      axios
        .post(this.urlBase + `/${id}`, formData, config)
        .then((response) => {
          this.$store.state.mostrarAlert = true;
          this.tituloAlert = "Marca editada com sucesso.";
          this.tipoAlert = "success";
          this.mensagemAlert =
            "O Id da Marca editada é: " + response.data.data.id;
          this.listarMarcas();
        })
        .catch((errors) => {
          this.$store.state.mostrarAlert = true;
          this.tituloAlert = "Erro ao editar a marca.";
          this.tipoAlert = "error";
          this.errorsAlertValidacao = errors.response.data.errors;
          this.mensagemAlert = errors.response.data.message;
        });
    },
    deletar() {
      let id = this.$store.state.item.id;

      let formData = new FormData();
      formData.append("_method", "delete");

      axios
        .post(this.urlBase + `/${id}`, formData)
        .then((response) => {
          this.$store.state.mostrarAlert = true;
          this.tituloAlert = "Marca deletada com sucesso.";
          this.tipoAlert = "success";
          this.mensagemAlert = "A marca de ID: " + id + ", foi deletada.";
          this.listarMarcas();
        })
        .catch((errors) => {
          this.$store.state.mostrarAlert = true;
          this.tituloAlert = "Erro ao deletar a marca.";
          this.tipoAlert = "error";
          this.errorsAlertValidacao = errors.response.data.errors;
          this.mensagemAlert = errors.response.data.message;
        });
    },
    fechar() {
      this.$store.state.mostrarAlert = false;
    },
    carregarImagem(e) {
      this.imagemMarca = e.target.files;
    },
    listarMarcas() {
      let url = this.urlBase + `?${this.filtro}${this.urlPaginacao}`;
      axios
        .get(url)
        .then((response) => {
          this.paginacao = response.data.links;
          this.listaMarcas = response.data.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    paginar(url) {
      if (url) {
        this.urlPaginacao = "&" + url.split("?")[1];
        this.listarMarcas();
        // console.log(this.urlBase);

        // axios.get(this.urlBase).then((response) => {
        //   this.listaMarcas = response.data.data;
        //   this.paginacao = response.data.links;
        // });
      }
    },
    pesquisar() {
      //   console.log(this.busca);
      let filtro = "";
      for (let b in this.busca) {
        if (this.busca[b]) {
          if (filtro != "") {
            filtro += ",";
          }
          filtro += `${b}:like:${this.busca[b]}`;
        }
      }
      this.urlPaginacao = "&page=1";
      this.filtro = "filtros=" + filtro;
      this.listarMarcas();
    },
  },
  mounted() {
    this.listarMarcas();
  },
};
</script>

<style>
</style>
