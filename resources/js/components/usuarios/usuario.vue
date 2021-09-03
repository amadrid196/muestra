<template>
<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
    <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ usuario</h6>
        <div class="card">
            <div class="card-body">
                <div v-if="isloading" class="row vh-50 align-items-center  justify-content-center">
                        <vue-ellipse-progress :gap="10" animation="reverse 700 400"
                        :noData="false"
                        :loading="isloading" :size="200" color="#ff6700"/>
                </div>
                <div class="form-row" v-if="!isloading">
                <div class="form-group col-12 col-md-8">
                <router-link to="/menu/usuarios/usuarios-crear" >
                    <button type="button" class="btn btn-primary" style="background-color:#ff6700; border-color: #ff6700">
                        Agregar usuario
                        <i class="bi bi-person-plus-fill" style="color: #fffff"></i>
                    </button>
                </router-link>
                </div>
                    <div class="form-group col-12 col-md-4">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                            <div class="input-group-text"><i class="bi bi-search text-verde"></i></div>
                            </div>
                                <input v-model="search" @keyup="getUser(1, search)" type="search" class="form-control" id="inputPassword4" placeholder="Busqueda">
                        </div>
                    </div>
                </div>
                    <div class="table-responsive" v-if="!isloading">
                        <table class="table table-bordered">
                        <thead>
                            <tr class="col-blue-tr alisup">
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Usuario</th>
                            <th>Departamento</th>
                            <th>Rol Usuario</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in user" v-bind:key="item.idusuarios">
                            <td>{{item.nombres}}</td>
                            <td>{{item.apellidos}}</td>
                            <td>{{item.usuario}}</td>
                            <td>{{item.departamento}}</td>
                            <td>{{item.rol}}</td>
                            <td>{{item.estado}}</td>
                            <td>
                            <router-link :to="{name: 'usuarios-crear', params: { data: item}}"> <i title="Editar" class="bi bi-pencil-square" ></i>
                            </router-link>
                            <a data-toggle="modal" data-target="#exampleModal" href="" v-on:click="obtenerId(item, 3)"><i title="Eliminar" class="bi bi-trash" ></i></a>
                            <a data-toggle="modal" data-target="#exampleModal" href="" v-on:click="obtenerId(item, 2)"><i title="Reiniciar" class="bi bi-envelope-fill" ></i></a>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <nav aria-label="Page navigation example" v-if="!isloading">
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
                    <button v-if="accion == '3'" type="button" class="btn btn-primary" v-on:click="eliminarUsuario">Aceptar</button>
                     <button v-if="accion == '2'" type="button" class="btn btn-primary" v-on:click="reinicioContrasena">Aceptar</button>
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
        user: [],
        datos: [],
        pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      id_usuario: '',
      accion: '',
      nombres: '',
      apellidos: '',
      usuario: '',
      correo: '',
      title: '',
      offset: 3,
      search: "",
      isloading: false
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
      getUser: function(page, search){
      let url = `/getUsuario?page=${page}&search=${search}`;
      axios
        .get(url)
        .then((response) => {
            this.user = response.data.usuarios.data;
            this.pagination = response.data.pagination;
        })
        .catch((err) => {
          console.log(err);
        });
      },

      obtenerId: function (data = [], accion = ''){
          this.id_usuario = data.id_usuario;
          this.nombres = data.nombres;
          this.apellidos = data.apellidos;
          this.usuario = data.usuario;
          this.correo = data.correo;
          this.accion = accion;
          if(accion == '3'){
            this.title = 'Si eliminas este registro no podras volver a visualizarlo.';
          }
          if(accion == '2'){
            this.title = 'Se enviara un correo con la nueva contraseña';
          }
      },
    eliminarUsuario: function(){
      let url = `/eliminarUsuario`;
      axios
        .post(url,{
            id_usuario: this.id_usuario,
            accion: this.accion
        })
        .then((response) => {
                $('#exampleModal').modal('hide')
        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.getUser(this.current_page, "");
        })
        .catch((err) => {
          console.log(err);
        });
        },
    reinicioContrasena: function(){
        this.isloading = true
      let url = `/reinicioContrasena`;
      axios
        .post(url,{
            id_usuario: this.id_usuario,
            nombres: this.nombres,
            apellidos: this.apellidos,
            correo: this.correo,
            usuario: this.usuario
        })
        .then((response) => {
                $('#exampleModal').modal('hide')
        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
         this.isloading = false
        this.getUser(this.current_page, "");
        })
        .catch((err) => {
          console.log(err);
        });
        },
    cambiarPagina: function (page, search) {
      this.pagination.current_page = page;
      this.getUser(page, search);
    },
  },
      created: function () {
        this.getUser(1, this.search);
    },
    mounted() {
        this.getUser(1, this.search);
    }
}
</script>

