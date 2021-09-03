<template>
    <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
        <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ <router-link to="/menu/rol">roles</router-link> \ {{title}}</h6>
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
                            <label for="inputEmail4">Nombre del Rol </label>
                            <input v-model="name" type="text" class="form-control" id="name" placeholder="escriba un rol de usuario ">
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.name}}</span></span>
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
                            <router-link to="/menu/rol" class="btn btn-danger text-white">Volver</router-link>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 1">
                            <button type="submit" class="btn btn-primary" v-on:click="postRol">Guardar</button>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 2">
                            <button type="submit" class="btn btn-primary" v-on:click="putRol">Guardar</button>
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
        name: '',
        idRol: '',
        estado: [],
        errors: [],
        errorUser: 0,
        isloading: false,
        title: "crear",
        tipoAccion: 1,
    }
},
methods:{

    getData: function(){
    if(this.$route.params.data){
        this.idRol = this.$route.params.data.id_rol;
        this.name = this.$route.params.data.descripcion;
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
          this.estado = respuesta.estados.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    postRol: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/rolPost";
      axios
        .post(url, {
          name: this.name,
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
            this.$router.push('/menu/rol')
        }
        });
    },

    putRol: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/putRol";
      axios
        .put(url, {
          name: this.name,
          idRol: this.idRol,
          id_estado: this.selectTipo.id_estado
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/rol')
        }
        });
    },
    checkForm: function () {
      this.errorUser = 1;
      this.errors = [];
      if (!this.name) {
        this.errors.push({'name': 'debes ingresar una descripcion valida'});
      }

      if (this.selectTipo.length) {
        this.errors.push({'estado': 'debes ingresar un estado valido'});
      }

      if(this.name && !this.selectTipo.length){
          this.errorUser = 0;
          return this.errorUser;
      }

      return this.errorUser;
      }
},
    created: function () {
        this.getData();
    },
      mounted() {

      }
}

</script>

