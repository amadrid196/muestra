<template>
<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
    <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ rol</h6>
        <div class="card">
            <div class="card-body">

                <div class="form-row">
                <div class="form-group col-12 col-md-8">
                <router-link to="/menu/rol-crear" >
                    <button type="button" class="btn btn-primary" style="background-color:#ff6700; border-color: #ff6700">
                        Agregar rol
                        <i class="bi bi-plus-square" style="color: #fffff"></i>
                    </button>
                </router-link>
                </div>
                    <div class="form-group col-12 col-md-4">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                            <div class="input-group-text"><i class="bi bi-search text-verde"></i></div>
                            </div>
                                <input v-model="search" @keyup="getRol(1, search)" type="search" class="form-control" id="inputPassword4" placeholder="Busqueda">
                        </div>
                    </div>

                </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <thead>
                            <tr class="col-blue-tr alisup">
                            <th>Tipo rol</th>
                            <th>estado</th>
                            <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in rol" v-bind:key="item.id_rol">
                            <td>{{item.descripcion}}</td>
                            <td>{{item.estado}}</td>
                            <td>
                            <router-link :to="{name: 'crear-rol', params: { data: item}}"> <i title="Editar" class="bi bi-pencil-square text-verde"></i>
                            </router-link>
<!--                             <a data-toggle="modal" data-target="#exampleModal" href="" v-on:click="obtenerId(item, 3)"><i title="Eliminar" class="bi bi-trash text-verde"></i></a> -->                            </td>
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
                    <button type="button" class="btn btn-primary" v-on:click="deleteRol">Aceptar</button>
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
import crearRol from '../roles/rol_crear.vue'
export default {
 components: {
    crearRol,
  },
  data() {
    return {
        rol: [],
        datos: [],
        pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      idrol: '',
      accion: '',
      title: '',
      offset: 3,
      search: "",
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
      getRol: function(page, search){
      let url = `/getRol?page=${page}&search=${search}`;
      axios
        .get(url)
        .then((response) => {
            this.rol = response.data.rols.data;
            this.pagination = response.data.pagination;
        })
        .catch((err) => {
          console.log(err);
        });
      },

      obtenerId: function (data = [], accion = ''){
          this.idrol = data.id_rol;
          this.accion = accion;
          if(accion == '3'){
            this.title = 'Si eliminas este registro no podras volver a visualizarlo.';
          }
      },
    deleteRol: function(){
      let url = `/deleteRol`;
      axios
        .post(url,{
            idRol: this.idrol,
            accion: this.accion
        })
        .then((response) => {
                $('#exampleModal').modal('hide')
        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.getRol(this.current_page, "");
        })
        .catch((err) => {
          console.log(err);
        });
        },
    cambiarPagina: function (page, search) {
      this.pagination.current_page = page;
      this.getRol(page, search);
    },
  },
      created: function () {
        this.getRol(1, this.search);
    },
    mounted() {
        this.getRol(1, this.search);
    }
}
</script>

