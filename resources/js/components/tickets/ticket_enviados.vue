<template>
<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
    <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \<router-link to="/menu/ticket/filtro-enviados"> filtro</router-link> \ enviados</h6>
        <div class="card">
            <div class="card-body">

                <div class="form-row">
                <div class="form-group col-12 col-md-8">
                    <p>
                        <router-link to="/menu/ticket/filtro-enviados">
                            <i class="bi bi-arrow-left-circle"> Volver</i>
                        </router-link>
                    </p>
                </div>
                    <div class="form-group col-12 col-md-4">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                            <div class="input-group-text"><i class="bi bi-search text-verde" ></i></div>
                            </div>
                            <input v-model="search" @keyup="getFiltroEnviados(1, search)" type="search" class="form-control" id="inputPassword4" placeholder="Busqueda">
                        </div>
                    </div>

                </div>
                    <div class="table-responsive" ref="content">
                        <table class="table table-bordered" id="my-table">
                        <thead>
                            <tr class="col-blue-tr alisup">
                            <th>Deparmento a Solicitar</th>
                            <th>Tipo Documento</th>
                            <th>Asunto</th>
                            <th>Fecha Solicitud</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in enviados" v-bind:key="item.idusuarios">
                            <td>{{item.departamento}}</td>
                            <td>{{item.documento}}</td>
                            <td>{{item.asunto}}</td>
                            <td>{{item.fecha}}</td>
                            <td>{{item.estado}}
                            </td>
                            <td>
                            <router-link :to="{name: 'ver-ticket', params: { data: item, tipo: 2}}"> <i class="bi bi-eye"></i>
                            </router-link>
                                <a data-toggle="modal" v-if="id_registro == 3" data-target="#exampleModal" href="" v-on:click="obtenerId(item, 2)"><i title="evaluar" class="bi bi-star"></i></a>
                                <a data-toggle="modal" v-if="id_registro == 3" data-target="#exampleModal" href="" v-on:click="obtenerId(item, 4)"><i title="rechazar" class="bi bi-bootstrap-reboot text-danger"></i></a>
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
                    <h5 class="modal-title"  v-if="accion == '2'">Mensaje de Alerta</h5>
                    <h5 class="modal-title"  v-if="accion == '4'">Rechazar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="accion == '2'">
                        <!-- Inputs de tipo Radio -->
                        <label class="radio" v-for="ev in evaluar" v-bind:key="ev.id_tipo_evaluacion">{{ev.descripcion}}
                            <input type="radio" checked="checked" name="radio" v-model="radio" :value="ev.id_tipo_evaluacion">
                            <i :class="ev.icon" class="check"></i>
                        </label>
                        <label class="">Comentario</label>
                        <input class="form-control" type="text" name="comentario" v-model="comentario" required>
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.comentario}}</span></span>
                    </div>
                    <div v-if="accion == '4'">
                        <!-- Inputs de tipo Radio -->
                        <label class="">Describa el motivo</label>
                        <input class="form-control" type="text" name="comentario" v-model="comentario" required>
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.comentario}}</span></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button v-if="accion == '2'" type="button" class="btn btn-primary" v-on:click="postEvaluar">Aceptar</button>
                    <button v-if="accion == '4'" type="button" class="btn btn-primary" v-on:click="postRechazar">Aceptar</button>
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
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import axios from "axios";
export default {
  data() {
    return {
        enviados: [],
        datos: [],
        evaluar:[],
        radio:'',
        rol:'',
        comentario:'',
        id_registro_documento:'',
        id_tipo_evaluacion:'',
        pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      idusuarios: '',
      accion: '',
      title: '',
      offset: 3,
      search: "",
      errors: []
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
      downloadPDF: function(){
                var pdf = new jsPDF();
                pdf.autoTable({ html: '#my-table' })
                pdf.save('table.pdf')
      },
      getFiltroEnviados: function(page, search){
        if(this.$route.params.data){
            localStorage.setItem('id_estado', JSON.stringify(this.$route.params.data.id_estado_registro_documento));
        }
        this.id_registro = JSON.parse(window.localStorage.getItem('id_estado'));
      let url = `/getFiltroEnviados?page=${page}&search=${search}&id=${this.id_registro}`;
      axios
        .get(url)
        .then((response) => {
            this.enviados = response.data.enviados.data;
            this.pagination = response.data.pagination;
        })
        .catch((err) => {
          console.log(err);
        });
      },
      gettipoEvaluacion: function(page, search){
      this.rol = JSON.parse(window.localStorage.getItem('id_rol'));
      let url = `/gettipoEvaluacion?page=${page}&search=${search}`;
      axios
        .get(url)
        .then((response) => {
            console.log('response',response)
            this.evaluar = response.data.tipoEvaluacions.data;
            this.pagination = response.data.pagination;
        })
        .catch((err) => {
          console.log(err);
        });
      },

      obtenerId: function (data = [], accion = ''){
          this.id_registro_documento = data.id_registro_documento;
          this.accion = accion;
          if(accion == '3'){
            this.title = 'Si eliminas este registro no podras volver a visualizarlo.';
          }
          if(accion == '1'){
            this.title = 'Desea activar el registro';
          }
          if(accion == '2'){
            this.title = 'Desea desactivar el registro';
          }
          if(accion == '4'){
            this.title = 'Esta seguro de Rechazar el ticket?.';
          }
      },
    postEvaluar: function(){
      let url = `/evaluarPost`;
      console.log('radio',this.radio)
      axios
        .post(url,{
            id_registro_documento: this.id_registro_documento,
            id_tipo_evaluacion: this.radio,
            comentario: this.comentario,
            accion: this.accion
        })
        .then((response) => {
                $('#exampleModal').modal('hide')
        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.getFiltroEnviados(this.current_page, "");
        })
        .catch((err) => {
          console.log(err);
        });
    },
    postRechazar: function(){
      let url = `/evaluarPost`;
      console.log('radio',this.radio)
      axios
        .post(url,{
            id_registro_documento: this.id_registro_documento,
            id_tipo_evaluacion: "2",
            comentario: this.comentario,
            accion: this.accion
        })
        .then((response) => {
                $('#exampleModal').modal('hide')
        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.getFiltroEnviados(this.current_page, "");
        })
        .catch((err) => {
          console.log(err);
        });
    },

    checkForm: function () {
      this.errorUser = 1;
      this.errors = [];
      if (!this.comentario) {
        this.errors.push({'comentario': 'Ingrese un comentario'});
      }
    },
    cambiarPagina: function (page, search) {
      this.pagination.current_page = page;
      this.getFiltroEnviados(page, search);
    },
  },
      created: function () {
        this.getFiltroEnviados(1, this.search);
        this.gettipoEvaluacion(1, this.search);
    },
    mounted() {
        this.getFiltroEnviados(1, this.search);
        this.gettipoEvaluacion(1, this.search);

    }


}
</script>

