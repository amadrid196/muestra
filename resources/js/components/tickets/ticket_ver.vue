<template>
<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
    <div class="col-12 col-md-12">
            <h6 v-if="tipo == '1'">
                <router-link to="/menu">menu</router-link> \<router-link to="/menu/ticket/filtro-recibidos"> filtro</router-link> \ recibidos
            </h6>
            <h6 v-if="tipo == '2'">
                <router-link to="/menu">menu</router-link> \<router-link to="/menu/ticket/filtro-enviados"> filtro</router-link> \ enviados
            </h6>
            <h6 v-if="tipo == '3'">
                <router-link to="/menu">menu</router-link> \<router-link to="/menu/ticket/aprobar"> filtro</router-link> \ aprobar
            </h6>
        <div class="card">
            <div class="card-body">

                <div class="form-row">
                <div class="form-group col-12 col-md-8">
                    <p>
                        <router-link to="/menu/ticket/ticket-enviados" v-if="tipo == '2'">
                            <i class="bi bi-arrow-left-circle"> Volver</i>
                        </router-link>
                        <router-link to="/menu/ticket/ticket-recibidos" v-if="tipo == '1'">
                            <i class="bi bi-arrow-left-circle"> Volver</i>
                        </router-link>
                        <router-link to="/menu/ticket/aprobar" v-if="tipo == '3'">
                            <i class="bi bi-arrow-left-circle"> Volver</i>
                        </router-link>
                    </p>

                    <a class="btn btn-success text-white" role="button" v-on:click="downloadPDF()"><i class="bi bi-file-earmark-arrow-down"></i> Descargar</a>

                </div>


                </div>
                    <div class="row" v-for="item in enviados" v-bind:key="item.idusuarios">
                        <div class="col-12 text-center">
                        </div>
                            <div class="table-responsive">
                                <table class="table table-borderless" id="my-table">
                                <thead class="thead-light">
                                        <tr class="text-center">
                                            <th colspan="4"><h1>{{item.documento}} <span  v-if="item.tipos"> - {{item.tipos}}</span><span  v-if="item.subtipos"> - {{item.subtipos}}</span></h1></th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>{{item.nombres}} {{item.apellidos}}<br></b>Remitente </td>
                                        <td></td>
                                        <td></td>
                                        <td v-if="tipo == 2 || tipo == 3 "><b>{{item.departamento}}<br></b>Departamento a Solicitar</td>
                                        <td v-if="tipo == 1"><b>{{item.departamento}}<br></b>Departamento Solicitante</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{item.asunto}} <br></b>Asunto</td>
                                        <td></td>
                                        <td></td>
                                        <td><b>{{item.fechaSolicitud}}<br></b>Fecha Creación </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{item.estado}}<br></b>Estado Actual</td>
                                        <td></td>
                                        <td></td>
                                        <td v-if="item.comentario" class="col-md-8">
                                            <b>{{item.comentario}}<br>
                                            </b>Comentario
                                        </td>
                                    </tr>
                                    <tr>
                                        <td v-if="item.tipoEvaluacion"><b>{{item.tipoEvaluacion}}<br></b>Resultado de Evaluación</td>
                                        <td></td>
                                        <td></td>
                                        <td v-if="item.evaluacion">
                                            <b>{{item.evaluacion}}<br>
                                            </b>Comentario de Evaluación
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" >{{item.descripcion}}</td>
                                    </tr>
                                    <tr>
                                        <td v-if="item.adjunto ">
                                         <a :href="item.adjunto" target="_blank"><i class="bi bi-file-earmark"></i> Ver adjunto</a>
                                        </td>
                                        <td v-if="item.adjunto2 ">
                                         <a :href="item.adjunto2" target="_blank"><i class="bi bi-file-earmark"></i> Ver adjunto</a>
                                        </td>
                                    </tr>

                                </tbody>
                                </table>
                            </div>
                    </div>
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
                    <div v-if="accion == '3'">
                        <i title="Editar" class="bi bi-trash row vh-50 align-items-center  justify-content-center" style="font-size: 4rem; color: #ff6700"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="deleteUser">Aceptar</button>
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
        tipo:'',
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
      id_registro: ''
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
                pdf.autoTable({
                    html: '#my-table',
                    headStyles: {
                    fillColor: [100, 170, 100],
                    fontSize: 18,
                    halign: 'center',
                    },
                    theme:'plain',
                    margin: { top: 50 },
                })
                pdf.save('table.pdf')
      },
      getFiltroEnviados: function(page, search){

        if(this.$route.params.data){
          localStorage.setItem('id_registro', JSON.stringify(this.$route.params.data.id_registro_documento));
          localStorage.setItem('tipo', JSON.stringify(this.$route.params.tipo));
        }
        this.id_registro = JSON.parse(window.localStorage.getItem('id_registro'));
        this.tipo = JSON.parse(window.localStorage.getItem('tipo'));
        let url;
        if(this.tipo == 3){
            url = `/getDetalleAprobar?page=${page}&search=${search}&id=${this.id_registro}`;
        }
        if(this.tipo == 2){
            url = `/getDetalleEnviados?page=${page}&search=${search}&id=${this.id_registro}`;
        }
        if(this.tipo == 1){
            url = `/getDetalleRecibido?page=${page}&search=${search}&id=${this.id_registro}`;
        }
      axios
        .get(url)
        .then((response) => {
            console.log(response);
            this.enviados = response.data.detalle.data;
            this.pagination = response.data.pagination;
        })
        .catch((err) => {
          console.log(err);
        });
      },

      obtenerId: function (data = [], accion = ''){
          this.idusuarios = data.idusuarios;
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
      },
    deleteUser: function(){
      let url = `/deleteUser`;
      axios
        .post(url,{
            idusuarios: this.idusuarios,
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
    cambiarPagina: function (page, search) {
      this.pagination.current_page = page;
      this.getFiltroEnviados(page, search);
    },
  },
      created: function () {
        this.getFiltroEnviados(1, this.search);

    },
    mounted() {
        this.getFiltroEnviados(1, this.search);
    }
}
</script>

