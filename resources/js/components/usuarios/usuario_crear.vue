<template>
    <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
        <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ <router-link to="/menu/usuarios">usuario</router-link> \ {{title}}</h6>
            <div class="card">
                <div class="card-body">
                <div v-if="isloading" class="row vh-50 align-items-center  justify-content-center">
                        <vue-ellipse-progress :gap="10" animation="reverse 700 400"
                        :noData="false"
                        :loading="isloading" :size="200" color="#ff6700"/>
                </div>
                    <form method="POST" v-if="!isloading">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="inputEmail4">Nombres</label>
                        <input v-model="nombres" type="nombres" class="form-control" id="nombres" placeholder="Nombres">
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.nombres}}</span></span>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="inputPassword4">Apellidos</label>
                        <input v-model="apellidos" type="nombres" class="form-control" id="apellidos" placeholder="Apellidos">
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red">{{error.apellidos}}</span></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Correo</label>
                            <input v-model="email" type="email" class="form-control" id="email" placeholder="Correo">
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.correo}}</span></span>
                        </div>
                    <div class="form-group col-md-6">
                        <label for="inputState">Rol</label>
                        <v-select
                            v-model="selectRol"
                            :options="tipoRol"
                            label="descripcion"
                            :clearable="false"
                        ></v-select>
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.tipoRol}}</span></span>
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputState">Estado</label>
                        <v-select
                            v-model="selectEstado"
                            :options="tipoEstado"
                            label="descripcion"
                            :clearable="false"
                        ></v-select>
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.tipoEstado}}</span></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputState">Departamento</label>
                        <v-select
                            v-model="selectDepto"
                            :options="departamentos"
                            label="descripcion"
                            :clearable="false"
                        ></v-select>
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.departamento}}</span></span>
                    </div>
                    </div>

                    </form>
                    <div class="form-row">
                        <div class="form-group pr-2">
                            <router-link to="/menu/usuarios" class="btn btn-danger text-white">Volver</router-link>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 1">
                            <button type="submit" class="btn btn-primary" v-on:click="postUsuario">Guardar</button>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 2">
                            <button type="submit" class="btn btn-primary" v-on:click="putUsuario">Guardar</button>
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
        selectRol: "Seleccione un rol",
        selectDepto: "Seleccione un departamento",
        selectEstado: "Seleccione un tipo",
        tipoRol: [],
        departamentos: [],
        tipoEstado: [],
        user: [],
        nombres: '',
        apellidos: '',
        email: '',
        password: '',
        errors: [],
        errorUser: 0,
        isloading: false,
        title: "crear",
        tipoAccion: 1,
        id_usuario: ''
    }
},
methods:{
      getDepartamento: function(){
    if(this.$route.params.data){
        this.id_usuario = this.$route.params.data.id_usuario;
        this.nombres = this.$route.params.data.nombres;
        this.apellidos = this.$route.params.data.apellidos;
        this.email = this.$route.params.data.correo;
        this.selectRol = {  id_rol: this.$route.params.data.id_rol,
                             descripcion: this.$route.params.data.rol}
        this.selectDepto = {  id_departamento: this.$route.params.data.id_departamento,
                             descripcion: this.$route.params.data.departamento}
        this.selectEstado = {  id_estado: this.$route.params.data.id_estado,
                             descripcion: this.$route.params.data.estado}
        this.title = "editar"
        this.tipoAccion = 2
    }
      let url = `/getDepartamento`;
      axios
        .get(url)
        .then((response) => {
            this.departamentos = response.data.departamentosAll;
        })
        .catch((err) => {
          console.log(err);
        });
      },
      getRol: function(){
      let url = `/getRol`;
      axios
        .get(url)
        .then((response) => {
            this.tipoRol = response.data.rols.data;
        })
        .catch((err) => {
          console.log(err);
        });
      },

      getEstado: function(){
      let url = `/getEstado`;
      axios
        .get(url)
        .then((response) => {
            this.tipoEstado = response.data.estados.data;
        })
        .catch((err) => {
          console.log(err);
        });
      },


    postUsuario: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/postUsuario";
      axios
        .post(url, {
          nombres: this.nombres,
          apellidos: this.apellidos,
          email: this.email,
          password: this.password,
          selectRol: this.selectRol.id_rol,
          selectDepto: this.selectDepto.id_departamento,
          selectEstado: this.selectEstado.id_estado,
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/usuarios')
        }
        });
    },

    putUsuario: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/putUsuario";
      axios
        .put(url, {
          id_usuario: this.id_usuario,
          nombres: this.nombres,
          apellidos: this.apellidos,
          email: this.email,
          password: this.password,
          selectRol: this.selectRol.id_rol,
          selectDepto: this.selectDepto.id_departamento,
          selectEstado: this.selectEstado.id_estado,
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/usuarios')
        }
        });
    },
    checkForm: function () {
      this.errorUser = 1;
      this.errors = [];
      if (!this.nombres) {
        this.errors.push({'nombres': 'debes ingresar un nombre'});
      }
      if (!this.apellidos) {
        this.errors.push({'apellidos': 'debes ingresar un apellido'});
      }
      if (!this.email) {
        this.errors.push({'correo': 'debes ingresar un correo'});
      }
      if (this.selectRol.length) {
        this.errors.push({'tipoRol': 'debes seleccionar un tipo de rol'});
      }
      if (this.selectDepto.length) {
        this.errors.push({'departamento': 'debes seleccionar un departamento'});
      }
      if (this.selectEstado.length) {
        this.errors.push({'tipoEstado': 'debes seleccionar un estado'});
      }
      if(this.nombres && this.apellidos && this.email && !this.selectRol.length && !this.selectDepto.length && !this.selectEstado.length
         || this.nombres && this.apellidos && this.email && this.tipoAccion == 2){
          this.errorUser = 0;
          return this.errorUser;
      }
    console.log(this.errorUser)
      return this.errorUser;
      }
},
    created: function () {
        this.getDepartamento();
        this.getRol();
        this.getEstado();
    },
      mounted() {

      }
}

</script>

