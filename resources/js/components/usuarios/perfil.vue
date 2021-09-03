<template>
    <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
        <div class="col-12 col-md-12">
            <h6>Perfil de Usuario</h6>
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
                            <input v-model="correo" type="email" class="form-control" id="email" placeholder="Correo" disabled>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.correo}}</span></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Departamento</label>
                            <input v-model="departamento" type="text" class="form-control" id="departamento" placeholder="Correo" disabled>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.correo}}</span></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Contraseña</label>
                            <input v-model="contraseña" type="password" class="form-control" id="contraseña" placeholder="Contraseña">
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.contraseña}}</span></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Verificar Contraseña</label>
                            <input v-model="confirContra" type="password" class="form-control" id="confirContra" placeholder="Verificar Contraseña">
                        </div>
                    </div>

                    </form>
                    <div class="form-row">
                        <div class="form-group pr-2">
                            <router-link to="/menu" class="btn btn-danger text-white">Volver</router-link>
                        </div>
                        <div class="form-group" v-if="!isloading">
                            <button type="submit" class="btn btn-primary" v-on:click="perfilUpdate">Guardar</button>
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
      nombres: "",
      apellidos: "",
      correo: "",
      numeroEmpleado: "",
      contraseña: "",
      confirContra: "",
      usuario: "",
      departamento: '',
      errors: [],
      errorUser: 0,
      isloading: false,
      title: "crear",
      tipoAccion: 1,
    };
  },
  methods: {
    getProfile: function() {
      let url = "/getPerfil";
      axios.get(url).then(res => {
          console.log(res.data.perfil[0]);

          this.nombres = res.data.perfil[0].nombres;
          this.apellidos = res.data.perfil[0].apellidos;
          this.correo = res.data.perfil[0].correo;
          this.usuario = res.data.perfil[0].usuario;
          this.departamento = res.data.perfil[0].departamento;
      });
    },
    perfilUpdate: function() {
      let url = "/perfil/update";
      this.isloading = true;
      if (this.contraseña == this.confirContra) {
        axios
          .put(url, {
            nombres: this.nombres,
            apellidos: this.apellidos,
            contraseña: this.contraseña,
          })
          .then(response => {
        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
            this.isloading = false;
            this.contraseña = "";
            this.confirContra = "";
          });
      } else {
        this.$toasted.show("Las contraseña no coincide", {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.contraseña = "";
        this.confirContra = "";
        this.isloading = false;
      }
    }
  },
  created: function(){
    this.getProfile();
  },
  mounted() {
    this.getProfile();
  }
};
</script>
