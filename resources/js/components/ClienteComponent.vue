<template>
  <div>
    <card-component titulo="Pesquisar Cliente">
      <template slot="conteudo">
        <input-component
          texto_label="Nome"
          texto_help="Opicional, campo para filtrar a pesquisa."
        >
          <input type="text" class="form-control" v-model="buscar.nome" />
        </input-component>
      </template>

      <template slot="rodape">
        <input
          type="button"
          class="btn btn-primary btn-sm float-end"
          value="Pesquisar"
          @click="pesquisar()"
        />
      </template>
    </card-component>

    <card-component titulo="Listagem de Clientes">
      <template slot="conteudo">
        <tabela-component
          :colunas="colunasTabela"
          :dados="listaClientes"
          :visualizar="{
            visivel: true,
            dataBsToggle: 'modal',
            dataBsTarget: '#showClienteModal',
          }"
          :editar="{
            visivel: true,
            dataBsToggle: 'modal',
            dataBsTarget: '#editarClienteModal',
          }"
          :excluir="{
            visivel: true,
            dataBsToggle: 'modal',
            dataBsTarget: '#excluirClienteModal',
          }"
        >
        </tabela-component>
      </template>
      <template slot="rodape">
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
          <div class="col-2">
            <input
              type="button"
              value="Adicionar"
              class="btn btn-primary btn-sm float-end"
              data-bs-toggle="modal"
              data-bs-target="#addClienteModal"
            />
          </div>
        </div>
      </template>
    </card-component>

    <modal-component id_modal="addClienteModal" titulo="Adicionar Cliente">
      <template v-slot:alert v-if="$store.state.mostrarAlert">
        <alert-component
          :titulo="tituloAlert"
          :tipo="tipoAlert"
          :mensagens_validacoes="errorsAlertValidacao"
          :mensagem="mensagemAlert"
        >
        </alert-component>
      </template>

      <template slot="conteudo">
        <input-component
          texto_label="Nome"
          texto_help="Favor preencher o nome do cliente."
        >
          <input type="text" class="form-control" v-model="nome" />
        </input-component>
      </template>
      <template slot="rodape">
        <button
          type="button"
          class="btn btn-secondary btn-sm"
          data-bs-dismiss="modal"
          @click="fechar()"
        >
          Fechar
        </button>
        <button class="btn btn-primary btn-sm" @click="salvar()">Salvar</button>
      </template>
    </modal-component>

    <modal-component id_modal="showClienteModal" titulo="Visualizar Cliente">
      <template slot="conteudo">
        <input-component texto_label="Nome">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-component>
        <input-component texto_label="Data de Criação">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.created_at"
            disabled
          />
        </input-component>
      </template>
      <template slot="rodape">
        <button
          type="button"
          class="btn btn-secondary btn-sm"
          data-bs-dismiss="modal"
          @click="fechar()"
        >
          Fechar
        </button>
      </template>
    </modal-component>

    <modal-component id_modal="editarClienteModal" titulo="Editar Cliente">
      <template v-slot:alert v-if="$store.state.mostrarAlert">
        <alert-component
          :titulo="tituloAlert"
          :tipo="tipoAlert"
          :mensagens_validacoes="errorsAlertValidacao"
          :mensagem="mensagemAlert"
        >
        </alert-component>
      </template>

      <template slot="conteudo">
        <input-component
          texto_label="Nome"
          texto_help="Favor preencher o nome do cliente."
        >
          <input
            type="text"
            class="form-control"
            v-model="$store.state.item.nome"
          />
        </input-component>
      </template>
      <template slot="rodape">
        <button
          type="button"
          class="btn btn-secondary btn-sm"
          data-bs-dismiss="modal"
          @click="fechar()"
        >
          Fechar
        </button>
        <button class="btn btn-primary btn-sm" @click="editar()">Salvar</button>
      </template>
    </modal-component>

    <modal-component titulo="Excluir Marca" id_modal="excluirClienteModal">
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
          <input-component texto_label="Nome do cliente">
            <input
              type="text"
              class="form-control"
              placeholder="Cliente"
              :value="$store.state.item.nome"
              disabled
            />
          </input-component>
        </div>
      </template>
      <template v-slot:rodape>
        <button
          type="button"
          class="btn btn-secondary btn-sm"
          data-bs-dismiss="modal"
          @click="fechar()"
        >
          Fechar
        </button>
        <button type="button" class="btn btn-danger btn-sm" @click="deletar()">
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
      urlBase: "http://127.0.0.1:8000/api/cliente",
      urlPaginacao: "",
      paginacao: [],
      filtros: "",
      listaClientes: [],
      colunasTabela: {
        id: { titulo: "Id", col: "id", tipo: "texto" },
        nome: { titulo: "Nome", col: "nome", tipo: "texto" },
        created_at: { titulo: "Data Criação", col: "created_at", tipo: "data" },
      },
      buscar: { nome: "" },
      nome: "",
      tituloAlert: "",
      tipoAlert: "",
      errorsAlertValidacao: [],
      mensagemAlert: "",
    };
  },
  methods: {
    listarClientes() {
      let url = this.urlBase + "?" + this.urlPaginacao + this.filtros;

      axios
        .get(url)
        .then((response) => {
          this.listaClientes = response.data.data;
          this.paginacao = response.data.links;
        })
        .catch((errors) => {
          console.log(errors.response.data);
        });
    },
    paginar(url) {
      if (url) {
        let page = url.split("?")[1];
        this.urlPaginacao = page;
        this.listarClientes();
      }
    },
    pesquisar() {
      this.filtros = "";
      if (this.buscar.nome) {
        let filtro = `&filtros=nome:like:${this.buscar.nome}`;
        this.filtros = filtro;
      }
      this.urlPaginacao = "pages=1";
      this.listarClientes();
    },
    fechar() {
      this.$store.state.mostrarAlert = false;
    },
    salvar() {
      let formData = new FormData();
      formData.append("nome", this.nome);

      axios
        .post(this.urlBase, formData)
        .then((response) => {
          this.tituloAlert = "Cliente cadastrado com sucesso.";
          this.mensagemAlert = `O id do cliente cadastrado foi: ${response.data.id}.`;
          this.tipoAlert = "success";
          this.$store.state.mostrarAlert = true;
          this.listarClientes();
        })
        .catch((errors) => {
          console.log(errors);
          this.tituloAlert = "Erro ao efetuar o cadastro do cliente.";
          this.tipoAlert = "error";
          this.errorsAlertValidacao = errors.response.data.errors;
          this.mensagemAlert = errors.response.data.message;
          this.$store.state.mostrarAlert = true;
        });
    },
    editar() {
      let formData = new FormData();
      formData.append("_method", "put");
      formData.append("nome", this.$store.state.item.nome);

      axios
        .post(this.urlBase + `/${this.$store.state.item.id}`, formData)
        .then((response) => {
          this.tituloAlert = "Cliente editado com sucesso.";
          this.mensagemAlert = `O id do cliente editado foi: ${response.data.id}.`;
          this.tipoAlert = "success";
          this.$store.state.mostrarAlert = true;
          this.listarClientes();
        })
        .catch((errors) => {
          console.log(errors);
          this.tituloAlert = "Erro ao efetuar a edição do cliente.";
          this.tipoAlert = "error";
          this.errorsAlertValidacao = errors.response.data.errors;
          this.mensagemAlert = errors.response.data.message;
          this.$store.state.mostrarAlert = true;
        });
    },
    deletar() {
      let id = this.$store.state.item.id;
      let formData = new FormData();
      formData.append("_method", "delete");
      axios
        .post(`${this.urlBase}/${id}`, formData)
        .then((response) => {
          this.$store.state.mostrarAlert = true;
          this.tituloAlert = "Cliente deletada com sucesso.";
          this.tipoAlert = "success";
          this.mensagemAlert = "O cliente de ID: " + id + ", foi deletado.";
          this.listarClientes();
        })
        .catch((errors) => {
          this.$store.state.mostrarAlert = true;
          this.tituloAlert = "Erro ao deletar o cliente.";
          this.tipoAlert = "error";
          this.errorsAlertValidacao = errors.response.data.errors;
          this.mensagemAlert = errors.response.data.message;
        });
    },
  },
  mounted() {
    this.listarClientes();
  },
};
</script>

<style>
</style>
