<template>
    <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
        <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ <router-link to="/menu/departamento">departamentos</router-link> \ {{title}}</h6>
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
                            <label for="inputEmail4">Nombre del departamento </label>
                            <input v-model="name" type="text" class="form-control" id="name" placeholder="">
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.name}}</span></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Nomenclatura</label>
                            <input v-model="nomenclatura" type="nombres" class="form-control" id="nomenclatura" placeholder="">
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red">{{error.nomenclatura}}</span></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputState">Estructura</label>
                            <v-select
                                v-model="selectEstructura"
                                :options="estructura"
                                label="descripcion"
                                :clearable="false"
                            ></v-select>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.estructura}}</span></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputState">Dependencia</label>
                            <v-select
                                v-model="selectDependencia"
                                :options="dependencia"
                                label="departamento"
                                :clearable="false"
                            ></v-select>
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.dependencia}}</span></span>
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
                    </div>

                    </form>
                    <div class="form-row">
                        <div class="form-group pr-2">
                            <router-link to="/menu/departamento" class="btn btn-danger text-white">Volver</router-link>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 1">
                            <button type="submit" class="btn btn-primary" v-on:click="postRol">Guardar</button>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 2">
                            <button type="submit" class="btn btn-primary" v-on:click="putDepartamento">Guardar</button>
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
        selectDependencia: "Seleccione",
        selectEstructura: "Seleccione",
        idDepartamento: '',
        id_dependencia: '',
        name: '',
        nomenclatura: '',
        estructura:[],
        dependencia:[],
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
    if(this.$route.params.data){
        this.id_dependencia = this.$route.params.data.id_dependencia;
        this.idDepartamento = this.$route.params.data.id_departamento;
        this.name = this.$route.params.data.departamento;
        this.nomenclatura = this.$route.params.data.nomenclatura;
        this.selectTipo = {  id_estado: this.$route.params.data.id_estado,
                             descripcion: this.$route.params.data.estado}
        this.selectEstructura = {  id_tipo_estructura: this.$route.params.data.id_tipo_estructura,
                             descripcion: this.$route.params.data.tipoEstructura}
        this.selectDependencia = {  id_departamento: this.$route.params.data.id_dept_dependencia,
                             departamento: this.$route.params.data.dependencia}
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

        let urle = `/gettipoEstructura`;
      axios
        .get(urle)
        .then((response) => {
          let respuesta = response.data;
          this.estructura = respuesta.tipoEstructuras.data;

        })
        .catch((err) => {
          console.log(err);
        });

        let urld = `/getDepartamento`;
      axios
        .get(urld)
        .then((response) => {
          let respuesta = response.data;
          this.dependencia = respuesta.departamentos.data;

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
    var url = "/departamentoPost";
      axios
        .post(url, {
          name: this.name,
          nomenclatura: this.nomenclatura,
          id_estado: this.selectTipo.id_estado,
          id_tipo_estructura: this.selectEstructura.id_tipo_estructura,
          id_dependencia: this.selectDependencia.id_departamento,
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/departamento')
        }
        });
    },

    putDepartamento: function(){
    if (this.checkForm()) {
        return;
      }
    this.isloading = true
    var url = "/putDepartamento";
      axios
        .put(url, {
          name: this.name,
          nomenclatura: this.nomenclatura,
          id_estado: this.selectTipo.id_estado,
          id_tipo_estructura: this.selectEstructura.id_tipo_estructura,
          id_dept_dependencia: this.selectDependencia.id_departamento,
          id_departamento: this.idDepartamento,
          id_dependencia: this.id_dependencia
        })
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/departamento')
        }
        });
    },
    checkForm: function () {
      this.errorUser = 1;
      this.errors = [];
      if (!this.name) {
        this.errors.push({'name': 'debes ingresar una descripcion valida'});
      }

      if (this.selectEstructura.length) {
        this.errors.push({'estructura': 'debes ingresar una estructura valida'});
      }

      if (this.selectDependencia.length) {
        this.errors.push({'dependencia': 'debes ingresar una dependencia valida'});
      }

      if (!this.nomenclatura) {
        this.errors.push({'nomenclatura': 'debes ingresar un dato valido'});
      }

      if (this.selectTipo.length) {
        this.errors.push({'estado': 'debes ingresar un estado valido'});
      }

      if(this.name && !this.selectEstructura.length && this.nomenclatura && !this.selectDependencia.length && !this.selectTipo.length){
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

