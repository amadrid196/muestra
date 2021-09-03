<template>
    <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
        <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ <router-link to="/menu/tipoDocumento">tipo documentos</router-link> \ {{title}}</h6>
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
                            <label for="inputEmail4">Nombre del Tipo documento </label>
                            <input v-model="name" type="text" class="form-control" id="name" placeholder="">
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.name}}</span></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputState">Categoria</label>
                            <v-select
                                v-model="selectDoc"
                                :options="documento"
                                label="descripcion"
                                :clearable="false"
                            ></v-select>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.documento}}</span></span>
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
                        <div class="form-group col-md-6">
                            <label for="subsubtipos">Subcategoria de Documento</label>
                            <select class="form-select" v-model="subtipos" aria-label="Default select example">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red">{{error.subtipos}}</span></span>
                        </div>
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
                    </div>

                    </form>
                    <div class="form-row">
                        <div class="form-group pr-2">
                            <router-link to="/menu/tipoDocumento" class="btn btn-danger text-white">Volver</router-link>
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
        selectDoc: "Seleccione un Documento",
        idtipoDocumento: '',
        name: '',
        autorizacion: '',
        subtipos:'',
        estado: [],
        documento: [],
        errors: [],
        errorUser: 0,
        isloading: false,
        title: "crear",
        tipoAccion: 1,
    }
},
methods:{

    getdata: function(){
    if(this.$route.params.data){
        console.log('editar',this.$route.params.data)
        this.idtipoDocumento = this.$route.params.data.id_tipos;
        this.name = this.$route.params.data.descripcion;
        this.autorizacion = this.$route.params.data.autorizacion;
        this.subtipos = this.$route.params.data.sub_tipos;

        this.selectTipo = {  id_estado: this.$route.params.data.id_estado,
                             descripcion: this.$route.params.data.estado}
        this.selectDoc = {  id_documentos: this.$route.params.data.id_documento,
                             descripcion: this.$route.params.data.documento}
        this.title = "editar"
        this.tipoAccion = 2
    }
      let url = `/getEstado`;
      axios
        .get(url)
        .then((response) => {
          let respuesta = response.data;
          this.estado = respuesta.estados.data;

        })
        .catch((err) => {
          console.log(err);
        });

        let urlD = `/getDocumento`;
         axios
        .get(urlD)
        .then((response) => {
          let respuesta = response.data;
          this.documento = respuesta.documentos.data;

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
    var url = "/tipoDocumentoPost";
      axios
        .post(url, {
          name: this.name,
          autorizacion: this.autorizacion,
          id_documento: this.selectDoc.id_documentos,
          sub_tipos: this.subtipos,
          id_estado:this.selectTipo.id_estado

        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/tipoDocumento')
        }
        });
    },

    putDocumento: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/puttipoDocumento";
      axios
        .put(url, {
          idtipodocumento: this.idtipoDocumento,
          name: this.name,
          autorizacion: this.autorizacion,
          id_documento: this.selectDoc.id_documentos,
          sub_tipos: this.subtipos,
          id_estado:this.selectTipo.id_estado
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/tipoDocumento')
        }
        });
    },
    checkForm: function () {
      this.errorUser = 1;
      this.errors = [];
      if (!this.name) {
        this.errors.push({'name': 'debes ingresar una descripcion valida'});
      }

      if (this.selectDoc.length) {
        this.errors.push({'documento': 'debes ingresar una categoria valida'});
      }

      if (this.autorizacion.length == 0) {
        this.errors.push({'autorizacion': 'debes ingresar un dato valido'});
      }


      if (this.subtipos.length == 0) {
        this.errors.push({'subtipos': 'debes ingresar un dato valido'});
      }

      if (this.selectTipo.length) {
        this.errors.push({'estado': 'debes ingresar un estado valido'});
      }

      if(this.name && !this.selectDoc.length && this.autorizacion && this.subtipos && !this.selectTipo.length || this.tipoAccion == 2 && this.name){
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

