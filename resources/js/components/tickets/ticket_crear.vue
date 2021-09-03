<template>
    <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
        <div class="col-12 col-md-12">
            <h6><router-link to="/menu">menu</router-link> \ <router-link to="/menu/ticket">ticket</router-link> \ {{title}}</h6>
            <div class="card">
                <div class="card-body">
                <div v-if="isloading" class="row vh-50 align-items-center  justify-content-center">
                        <vue-ellipse-progress :gap="10" animation="reverse 700 400"
                        :noData="false"
                        :loading="isloading" :size="200" color="#64AA64"/>
                </div>
                    <form method="POST" v-if="!isloading">
                    <div class="form-row" v-if="tipos == 1">
                    <div class="form-group col-md-6" v-if="tipo">
                        <label for="inputState">Tipo</label>
                        <v-select
                            v-model="selectTipo"
                            :options="tipo"
                            label="descripcion"
                            :clearable="false"
                             @input="getSubTipoDocumento"
                        ></v-select>
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.TipoUsuario}}</span></span>
                    </div>
                    <div class="form-group col-md-6" v-if="selectTipo.sub_tipos == 1">
                        <label for="inputState">Sub Tipo</label>
                        <v-select
                            v-model="selectSubTipo"
                            :options="sub_tipo"
                            label="descripcion"
                            :clearable="false"
                        ></v-select>
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.TipoUsuario}}</span></span>
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputState">Departamentos</label>
                        <v-select
                            v-model="selectDepto"
                            :options="departamento"
                            label="descripcion"
                            :clearable="false"
                            taggable
                            multiple
                            push-tags
                        ></v-select>
                        <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.TipoUsuario}}</span></span>
                    </div>

                     <div class="form-group col-md-6">
                            <label for="inputEmail4">Asunto</label>
                            <input v-model="asunto" type="nombres" class="form-control" id="nombres" placeholder="Ingrese un Asunto">
                            <span v-if="errors.length"><span v-for="error of errors" v-bind:key="error.length" style="color:red"> {{error.nombres}}</span></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="exampleFormControlTextarea1">Descripción</label>
                            <textarea  v-model="descripcion" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 preview">
                        <a :href="file" download>Descargar</a>
                        <img :src="file" alt="" type="image/x-icon">
                        </div>
                        <div class="form-group col-md-6">
                        <a :href="file" download>Descargar</a>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="exampleFormControlFile1">Documento</label>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1" @change="getFile1" required >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleFormControlFile1">Documento (opcional)</label>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1" @change="getFile2" required>
                        </div>
                    </div>
                    </form>
                    <div class="form-row">
                        <div class="form-group pr-2">
                            <router-link to="/menu/ticket" class="btn btn-danger text-white">Volver</router-link>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 1">
                            <button type="submit" class="btn btn-primary" v-on:click="postTicket">Guardar</button>
                        </div>
                        <div class="form-group" v-if="!isloading && tipoAccion== 2">
                            <button type="submit" class="btn btn-primary" v-on:click="tipoAccion">Guardar</button>
                        </div>
                        </div>
                    </div>
            </div>
        </div>
    </main>
</template>
<script>
import "vue-select/dist/vue-select.css";
import "vue-multiselect/dist/vue-multiselect.min.css";
import axios from "axios";
export default {
    props:['data'],
  data() {
    return {
        selectTipo: "Seleccione un tipo documento",
        selectSubTipo: "Seleccione un sub tipo documento",
        selectDepto: [],
        departamento: [],
        asunto: '',
        descripcion: '',
        tipo: [],
        sub_tipo: [],
        errors: [],
        errorUser: 0,
        isloading: false,
        title: "crear",
        tipoAccion: 1,
        idUsuario: '',
        tipos: null,
        sub_tipos: null,
        id_documento: '',
        autoTipo: '',
        autoSubTipo: '',
        file: null,
        file2: null,
        autoDoc: '',
        evaluacion: '',
    }
},
methods:{

    getDatos: function(){
    if(this.$route.params.data){
        this.tipos = this.$route.params.data.tipos
        this.id_documento = this.$route.params.data.id_documentos
        this.autoDoc = this.$route.params.data.autorizacion
        this.evaluacion = this.$route.params.data.evaluacion
        this.selectDepto = {id_departamento: this.$route.params.data.id_departamento_recibe,
                            descripcion: this.$route.params.data.departamento}
        this.asunto = this.$route.params.data.asunto
        this.descripcion = this.$route.params.data.descripcion
        this.file = this.$route.params.data.adjunto
        this.file2 = this.$route.params.data.adjunto2
    }
    console.log(this.$route.params.data);
        if(this.id_documento == ''){
            this.$router.push('/menu/ticket')
        }
    this.getTipoDocumento(this.id_documento)
    },

    getTipoDocumento: function(id_documento){
      let url = `/getTipoDocumento?&id_documento=${id_documento}`;
      axios
        .get(url)
        .then((response) => {
          let respuesta = response.data;
          this.tipo = respuesta.query;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getTicketDepto: function(){
      let url = `/getTicketDepto`;
      axios
        .get(url)
        .then((response) => {
          let respuesta = response.data;
          this.departamento = respuesta.departamentos;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getSubTipoDocumento: function(){
            let url = `/getSubTipoDocumento?&id_tipo=${this.selectTipo.id_tipos}`;
            axios
                .get(url)
                .then((response) => {
                let respuesta = response.data;
                this.sub_tipo = respuesta.query;
                })
                .catch((err) => {
                console.log(err);
                });
    },
        getFile1(event) {
            let file = event.target.files[0];
            this.file = file;
        },
        getFile2(event) {
            let file = event.target.files[0];
            this.file2 = file;
        },
    postTicket: function(){
    console.log(this.selectDepto)
    var array =  JSON.stringify(this.selectDepto);
    let formData = new FormData();
    formData.append("file", this.file ?? []);
    formData.append("file2", this.file2 ?? []);
    formData.append("selectTipo", this.selectTipo.id_tipos ?? '');
    formData.append("id_documento", this.id_documento);
    formData.append("selectSubTipo", this.selectSubTipo.id_sub_tipos ?? '');
    formData.append("selectDepto", array);
    formData.append("asunto", this.asunto);
    formData.append("descripcion", this.descripcion);
    formData.append("autoTipo", this.selectTipo.autorizacion);
    formData.append("autoSubTipo", this.selectSubTipo.autorizacion ?? '');
    formData.append("autoDoc", this.autoDoc ?? '');
    formData.append("evaluacion", this.evaluacion ?? '');
    formData.append("tipos", this.tipos ?? '');
    formData.append("sub_tipos", this.selectTipo.sub_tipos ?? '');

    this.isloading = true
    var url = "/postTicket";
      axios
        .post(url, formData)
        .then((response) => {

        this.$toasted.show(response.data.msj, {
                type : "error",
                position: "bottom-right",
                duration : 3000
            });
        this.isloading = false;
        if(response.data.success == true){
            this.$router.push('/menu/ticket')
        }
        });

    },
    vaciar: function (){
        this.selectDepto = []
    },
    checkForm: function () {
      this.errorUser = 1;
      this.errors = [];
      return this.errorUser;
      }

},
    created: function () {
        this.getDatos();
        this.getTicketDepto();
        this.vaciar();
    },
      mounted() {

      },
}

</script>

