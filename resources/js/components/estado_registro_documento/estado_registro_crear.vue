<template>
    <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
        <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ <router-link to="/menu/estado-registro">estado-registro</router-link> \ {{title}}</h6>
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
                        <label for="inputEmail4">Nombre de estado </label>
                        <input v-model="name" type="text" class="form-control" id="name" placeholder="Ejemplo 'Activo' ">
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.name}}</span></span>
                        </div>
                    </div>

                    </form>
                    <div class="form-row">
                        <div class="form-group pr-2">
                            <router-link to="/menu/estado-registro" class="btn btn-danger text-white">Volver</router-link>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 1">
                            <button type="submit" class="btn btn-primary" v-on:click="estadoRegistroPost">Guardar</button>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 2">
                            <button type="submit" class="btn btn-primary" v-on:click="putEstadoRegistro">Guardar</button>
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
        name: '',
        idEstado: '',
        errors: [],
        errorUser: 0,
        isloading: false,
        title: "crear",
        tipoAccion: 1,
    }
},
methods:{

    getdataEstado: function(){
        if(this.$route.params.data){
            this.idEstado = this.$route.params.data.id_estado_registro_documento;
            this.name = this.$route.params.data.descripcion;
            this.title = "editar"
            this.tipoAccion = 2
        }

    },
    estadoRegistroPost: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/estadoRegistroPost";
      axios
        .post(url, {
          name: this.name,
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/estado-registro')
        }
        });
    },

    putEstadoRegistro: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/putEstadoRegistro";
      axios
        .put(url, {
          name: this.name,
          idEstado: this.idEstado,
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/estado-registro')
        }
        });
    },
    checkForm: function () {
      this.errorUser = 1;
      this.errors = [];
      if (!this.name) {
        this.errors.push({'name': 'debes ingresar un estado valido'});
      }

      if(this.name){
          this.errorUser = 0;
          return this.errorUser;
      }

      return this.errorUser;
      }
},
    created: function () {
        this.getdataEstado();
    },
      mounted() {

      }
}

</script>

