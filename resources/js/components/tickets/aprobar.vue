<template>
<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
    <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link>\ aprobar</h6>
        <div class="card">
            <div class="card-body">

                <div class="form-row">
                <div class="form-group col-12 col-md-8">

                </div>
                    <div class="form-group col-12 col-md-4">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                            <div class="input-group-text"><i class="bi bi-search text-verde" ></i></div>
                            </div>
                            <input v-model="search" @keyup="getTicketAprobar(1, search)" type="search" class="form-control" id="inputPassword4" placeholder="Busqueda">
                        </div>
                    </div>

                </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <thead>
                            <tr class="col-blue-tr alisup">
                            <th>Departamento Solicitante</th>
                            <th>Tipo Documento</th>
                            <th>Asunto</th>
                            <th>Fecha Solicitud</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in aprobar" v-bind:key="item.idusuarios">
                            <td>{{item.departamento}} / {{item.nombres}} {{item.apellidos}}</td>
                            <td>{{item.documento}}</td>
                            <td>{{item.asunto}}</td>
                            <td>{{item.fecha}}</td>
                            <td>{{item.estado}}</td>
                            <td>
                            <a v-if="id_rol != 5" data-toggle="modal" data-target="#exampleModal" href="" v-on:click="obtenerId(item, 1)"><i title="Aprobar" class="bi bi-check-circle-fill"></i></a>
                            <router-link :to="{name: 'ver-ticket', params: { data: item, tipo: 3}}"> <i class="bi bi-eye"></i>
                            </router-link>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <li class="page-item " v-if="pagination.current_page > 1">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, search)">Anterior</a>
                        </li>
                        <li class="page-item disabled" v-else >
                        <a class="page-link" href="#">Anterior</a>
                        </li>
                        <li class="page-item"
                        v-for="page in pagesNumber"
                        :key="page"
                        :class="page == isActived ? 'active' : ''"
                        >
                            <a class="page-link" href="#"
                             @click.prevent="cambiarPagina(page, search)"
                             v-text="page"
                            ></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link" href="#"
                        @click.prevent="cambiarPagina(pagination.current_page + 1, search)">Siguiente</a>
                        </li>
                        <li class="page-item disabled" v-else>
                        <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                    </nav>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mensaje de Alerta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{title}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="aprobarTicket">Aceptar</button>
                    <button type="button" class="btn btn-primary" v-on:click="denegarTickets">Denegar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</main>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
        aprobar: [],
        datos: [],
        pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      id_registro_documento: '',
      id_documento: '',
      accion: '',
      title: '',
      offset: 3,
      search: "",
      id_rol:''
    }
  },
    computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }
      let from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      let to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      let pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  methods:{
      getTicketAprobar: function(page, search){
        this.id_rol= JSON.parse(window.localStorage.getItem('id_rol'));
        let url = `/getTicketAprobar?page=${page}&search=${search}`;
      axios
        .get(url)
        .then((response) => {

            this.aprobar = response.data.aprobar.data;
            this.pagination = response.data.pagination;
        })
        .catch((err) => {
          console.log(err);
        });
      },

      obtenerId: function (data = [], accion = ''){
          console.log(data)
          this.id_registro_documento = data.id_registro_documento;
          this.accion = accion;
          this.id_documento = data.id_documento;
          if(accion == '1'){
            this.title = 'Presiona aceptar para aprobar el documento / tambien puede denegar esta solicitud';
          }
      },
    aprobarTicket: function(){
      let url = `/aprobarTicket`;
      axios
        .post(url,{
            id_registro_documento: this.id_registro_documento,
            accion: this.accion,
            id_documento: this.id_documento,
        })
        .then((response) => {
                $('#exampleModal').modal('hide')
        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.getTicketAprobar(this.current_page, "");
        })
        .catch((err) => {
          console.log(err);
        });
        },

    denegarTickets: function(){
      let url = `/denegarTickets`;
      axios
        .post(url,{
            id_registro_documento: this.id_registro_documento,
            accion: this.accion,
            id_documento: this.id_documento,
        })
        .then((response) => {
                $('#exampleModal').modal('hide')
        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.getTicketAprobar(this.current_page, "");
        })
        .catch((err) => {
          console.log(err);
        });
        },
    cambiarPagina: function (page, search) {
      this.pagination.current_page = page;
      this.getTicketAprobar(page, search);
    },
  },
      created: function () {
        this.getTicketAprobar(1, this.search);
    },
    mounted() {
        this.getTicketAprobar(1, this.search);
    }
}
</script>

