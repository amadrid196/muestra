<template v-if="menu==0">
  <div>
<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">

    <div class="card text-center text-dark bg-white" >
        <div class="card-body textProceso ">
            <span>Tipos de Documentos</span>
        </div>
    </div>
    <br>
    <div class="row">
      <!-- @if (session::get('tipoUsuarioId') == 1) -->
<div class="col-sm-3" v-for="item in documentos" v-bind:key="item.id_documentos">

<router-link :to="{name: 'crear-ticket', params: { data: item}}"  class="text-white nav-item nav-link col-sm-12 col-md-3 d-inline p-3" >
      <a href=""  style="color: black">
        <div>
          <div class="card cardHome">
            <div class="card-body">
              <!-- <router-link to='/usuarios' :class=" menu == 1 ? 'tab_activo':'' "> -->
              <i class="bi bi-files" style="font-size: 5rem; color: #64AA64"></i>
              <!-- </router-link> -->
              <h5>{{item.descripcion}}</h5>
            </div>
          </div>
        </div>
      </a>
</router-link>
</div>

      <!-- @endif -->
    </div>
    <!-- @endif -->
    </main>
  </div>
</template>
<script>
export default {
      data() {
    return {
        documentos: [],
        }
    },
methods: {
    getAllDocumentos: function() {
        axios.get('/getAllDocumentos').then((response)=>{
            this.documentos = response.data.query;
        }).catch((err) => {
          console.log(err);
        });
    },
},
  mounted() {
      this.getAllDocumentos()
  },

    created: function () {
        this.getAllDocumentos();
    },
}
</script>
