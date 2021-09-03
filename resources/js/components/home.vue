<template v-if="menu==0">
  <div>
<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3" >

    <div class="card text-center text-dark bg-white" >
        <div class="card-body textProceso ">

        <div>
            <img src="/img/Logos-Puerto.png" alt="" style="height:100px">
        </div>
            <span>MUNICIPALIDAD DE PUERTO CORTÉS</span>
        </div>

    </div>
    <br>
    <div class="row">
      <!-- @if (session::get('tipoUsuarioId') == 1) -->

<router-link to="/menu" class="text-white nav-item nav-link col-sm-12 col-md-3 d-inline p-3" >
      <a href=""  style="color: black">
        <div>
          <div class="card cardHome">
            <div class="card-body">
              <!-- <router-link to='/usuarios' :class=" menu == 1 ? 'tab_activo':'' "> -->
              <i class="bi bi-house-fill" style="font-size: 5rem; color: #64AA64"></i>
              <!-- </router-link> -->
              <h5>Inicio</h5>
            </div>
          </div>
        </div>
      </a>
</router-link>
<router-link to="/menu/usuarios" class="text-white nav-item nav-link col-sm-12 col-md-3 d-inline p-3" v-if="id_rol == 1">
      <a href=""  style="color: black">
        <div>
          <div class="card cardHome">
            <div class="card-body">
              <!-- <router-link to='/usuarios' :class=" menu == 1 ? 'tab_activo':'' "> -->
              <i class="bi bi-people-fill" style="font-size: 5rem; color: #64AA64"></i>
              <!-- </router-link> -->
              <h5>Nuevo Usuario</h5>
            </div>
          </div>
        </div>
      </a>
</router-link>

<router-link to="/menu/ticket" class="text-white nav-item nav-link col-sm-12 col-md-3 d-inline p-3">
      <a href=""  style="color: black">
        <div>
          <div class="card cardHome">
            <div class="card-body">
              <!-- <router-link to='/usuarios' :class=" menu == 1 ? 'tab_activo':'' "> -->
              <i class="bi bi-journals" style="font-size: 5rem; color: #64AA64"></i>
              <!-- </router-link> -->
              <h5>Nuevo Ticket</h5>
            </div>
          </div>
        </div>
      </a>
</router-link>
<router-link to="/menu/ticket/filtro-enviados" class="text-white nav-item nav-link col-sm-12 col-md-3 d-inline p-3">
      <a href=""  style="color: black">
        <div>
          <div class="card cardHome">
            <div class="card-body">
              <!-- <router-link to='/usuarios' :class=" menu == 1 ? 'tab_activo':'' "> -->
              <i class="bi bi-arrow-right-square" style="font-size: 5rem; color: #64AA64"></i>
              <!-- </router-link> -->
              <h5>Tickets Enviados</h5>
            </div>
          </div>
        </div>
      </a>
</router-link>
<router-link to="/menu/ticket/filtro-recibidos" class="text-white nav-item nav-link col-sm-12 col-md-3 d-inline p-3">
      <a href=""  style="color: black">
        <div>
          <div class="card cardHome">
            <div class="card-body">
              <!-- <router-link to='/usuarios' :class=" menu == 1 ? 'tab_activo':'' "> -->
              <i class="bi bi-save" style="font-size: 5rem; color: #64AA64"></i>
              <!-- </router-link> -->
              <h5>Tickets Recibidos</h5>
            </div>
          </div>
        </div>
      </a>
</router-link>
<router-link to="/menu/ticket/aprobar" class="text-white nav-item nav-link col-sm-12 col-md-3 d-inline p-3">
      <a href=""  style="color: black">
        <div>
          <div class="card cardHome">
            <div class="card-body">
              <!-- <router-link to='/usuarios' :class=" menu == 1 ? 'tab_activo':'' "> -->
              <i class="bi bi-bookmark-check" style="font-size: 5rem; color: #64AA64"></i>
              <!-- </router-link> -->
              <h5>Tickets Por Aprobar</h5>
            </div>
          </div>
        </div>
      </a>
</router-link>
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
        id_rol: '',
        documentos: [],
        count:0,
    }
  },
methods: {
    getUsuarioRol: function (){
            this.id_rol= JSON.parse(window.localStorage.getItem('id_rol'));
    },
    getTicketEnviados: function() {
        axios.get('/getTicketEnviados').then((response)=>{
            console.log('data',response)
            this.documentos = response.data.enviados;
        }).catch((err) => {
          console.log(err);
        });
    },
},
  mounted() {
      this.getUsuarioRol();
      this.getTicketEnviados();
  }
}
</script>
