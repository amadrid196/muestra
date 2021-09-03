<template>
    <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
        <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ <router-link to="/menu/documento">documentos</router-link> \ {{title}}</h6>
            <div class="card">
                <div class="card-body">
                <div v-if="isloading" class="row vh-50 align-items-center  justify-content-center">
                        <vue-ellipse-progress :gap="10" animation="reverse 700 400"
                        :noData="false"
                        :loading="isloading" :size="200" color="#64AA64"/>
                </div>
                    <form method="POST" v-if="!isloading">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Nombre del documento </label>
                            <input v-model="name" type="text" class="form-control" id="name" placeholder="">
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.name}}</span></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="evaluacion">Evaluacion</label>
                            <select class="form-select" v-model="evaluacion" aria-label="Default select example">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red">{{error.evaluacion}}</span></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputState">Estado</label>
                            <v-select
                                v-model="selectTipo"
                                :options="estado"
                                label="descripcion"
                                :clearable="false"
                            ></v-select>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.estado}}</span></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tipos">Tipo Documento</label>
                            <select class="form-select" v-model="tipos" aria-label="Default select example">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red">{{error.tipos}}</span></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="autorizacion">Autorizacion</label>
                            <select class="form-select" v-model="autorizacion" aria-label="Default select example">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red">{{error.autorizacion}}</span></span>
                        </div>
                    </div>
                    </form>
                     <div class="form-row">
                        <div class="form-group pr-2">
                            <router-link to="/menu/documento" class="btn btn-danger text-white">Volver</router-link>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 1">
                            <button type="submit" class="btn btn-primary" v-on:click="postDocumento">Guardar</button>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 2">
                            <button type="submit" class="btn btn-primary" v-on:click="putDocumento">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
import "vue-select/dist/vue-select.css";
import axios from "axios";
export default {
    props:['data'],
  data() {
    return {
        selectTipo: "Seleccione un estado",
        idDocumento: '',
        name: '',
        evaluacion: '',
        autorizacion: '',
        tipos:'',
        estado: [],
        errors: [],
        errorUser: 0,
        isloading: false,
        title: "crear",
        tipoAccion: 1,
    }
},
methods:{

    getdata: function(){
        console.log(this.$route.params.data);
    if(this.$route.params.data){
        this.idDocumento = this.$route.params.data.id_documentos;
        this.autorizacion = this.$route.params.data.autorizacion;
        this.name = this.$route.params.data.descripcion;
        this.evaluacion = this.$route.params.data.evaluacion;
        this.tipos = this.$route.params.data.tipos;

        this.selectTipo = {  id_estado: this.$route.params.data.id_estado,
                             descripcion: this.$route.params.data.estado}
        this.title = "editar"
        this.tipoAccion = 2
    }
      let url = `/getEstado`;
      axios
        .get(url)
        .then((response) => {
          let respuesta = response.data;
          this.estado = this.estado = respuesta.estados.data;

        })
        .catch((err) => {
          console.log(err);
        });
    },
    postDocumento: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/documentoPost";
      axios
        .post(url, {
          name: this.name,
          evaluacion: this.evaluacion,
          tipos: this.tipos,
          id_estado:this.selectTipo.id_estado,
          autorizacion: this.autorizacion

        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/documento')
        }
        });
    },

    putDocumento: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/putDocumento";
      axios
        .put(url, {
          iddocumento: this.idDocumento,
          name: this.name,
          evaluacion: this.evaluacion,
          tipos: this.tipos,
          id_estado: this.selectTipo.id_estado,
          autorizacion: this.autorizacion
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/documento')
        }
        });
    },
    checkForm: function () {
      this.errorUser = 1;
      this.errors = [];
      if (!this.name) {
        this.errors.push({'name': 'debes ingresar una descripcion valida'});
      }

      if (!this.evaluacion && this.tipoAccion == 1) {
        this.errors.push({'evaluacion': 'debes ingresar un dato valido'});
      }

      if (!this.tipos && this.tipoAccion == 1) {
        this.errors.push({'tipos': 'debes ingresar un dato valido'});
      }

      if (this.selectTipo.length) {
        this.errors.push({'estado': 'debes ingresar un estado valido'});
      }

      if (!this.autorizacion) {
        this.errors.push({'autorizacion': 'debes ingresar un dato valido'});
      }

      if(this.name && this.evaluacion && this.tipos && !this.selectTipo.length && this.autorizacion || this.tipoAccion == 2 && this.name){
          this.errorUser = 0;
          return this.errorUser;
      }

      return this.errorUser;
      }
},
    created: function () {
        this.getdata();
    },
      mounted() {

      }
}

</script>

