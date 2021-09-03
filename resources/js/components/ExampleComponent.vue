<template>
    <div>
        <nav class="navbar navbar-expand-sm fixed-top fondo_azul">

            <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="true"
                aria-label="Toggle navigation"
            >
                <i class="bi bi-list-task text-white"></i>
            </button>
            <a class="navbar-brand text-white" href="/menu">
                <img
                    src="/img/Logos-Puerto.png"
                    alt=""
                    class="rounded-circle avatar"
                />
            </a>
            <ul class="navbar-nav flex-row ml-md-auto d-md-flex ">
                <!-- User Account Menu -->

                <li class="nav-item dropdown show">
                    <a
                        class="navbar-item nav-link dropdown-toggle mr-md-2 menuNav"
                        data-toggle="dropdown"
                        href="#!"
                        aria-expanded="true"
                        data-animation="scale-up"
                        role="button"
                    >
                        <span class="avatar-online">
                            <img
                                src="/img/computerUserIcon.png"
                                alt="..."
                                class="rounded-circle avatar"
                            />
                            <i></i>
                        </span>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-right"
                        aria-labelledby="bd-versions"
                        style="    width: 104px;
                        position: absolute;
                        top: 0px;
                        opacity: 1;
                        min-width:7rem"
                    >
                        <li>
                            <router-link
                                to="/menu/usuario/perfil"
                                class="nav-link"
                                ><i class="bi bi-person-fill"></i>
                                Pefil</router-link
                            >
                        </li>
                        <li>
                            <a href="/salir" class="nav-link"
                                ><i class="bi bi-box-arrow-right"></i> Salir</a
                            >
                        </li>
                    </div>
                </li>
            </ul>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <nav
                    class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar collapse show fondo_azul"
                    id="navbarSupportedContent"
                >
                    <div>
                        <span class="text-white divNav">NAVEGACION</span>
                    </div>
                    <ul class="nav nav-pills magen c-icon">
                        <li class="nav-item ">
                            <router-link
                                to="/menu"
                                class="text-white nav-item nav-link tab_item"
                            >
                                <i class="bi bi-house-fill"></i> Inicio
                            </router-link>
                        </li>
                        <li class="nav-item " v-if="id_rol == 1">
                            <router-link
                                to="/menu/estado"
                                class="text-white nav-item nav-link tab_item"
                            >
                                <i class="bi bi-people-fill"></i> Estados
                            </router-link>
                        </li>
                        <li class="nav-item " v-if="id_rol == 1">
                            <router-link
                                to="/menu/estado-registro"
                                class="text-white nav-item nav-link tab_item"
                            >
                                <i class="bi bi-people-fill"></i> Estados Ticket
                            </router-link>
                        </li>
                        <li class="nav-item " v-if="id_rol == 1">
                            <router-link
                                to="/menu/rol"
                                class="text-white nav-item nav-link tab_item"
                            >
                                <i class="bi bi-people-fill"></i> Roles
                            </router-link>
                        </li>
                        <li class="nav-item " v-if="id_rol == 1">
                            <router-link
                                to="/menu/tipoEstructura"
                                class="text-white nav-item nav-link tab_item"
                            >
                                <i class="bi bi-people-fill"></i> Tipo
                                Estructura
                            </router-link>
                        </li>
                        <li class="nav-item " v-if="id_rol == 1">
                            <router-link
                                to="/menu/departamento"
                                class="text-white nav-item nav-link tab_item"
                            >
                                <i class="bi bi-people-fill"></i> Departamentos
                            </router-link>
                        </li>
                        <li class="nav-item " v-if="id_rol == 1">
                            <router-link
                                to="/menu/usuarios"
                                class="text-white nav-item nav-link tab_item"
                            >
                                <i class="bi bi-people-fill"></i> Usuarios
                            </router-link>
                        </li>
                        <li class="nav-item" v-if="id_rol == 1">
                            <a
                                class="text-white nav-item nav-link dropdown-toggler tab_item"
                                data-toggle="collapse"
                                data-target="#navbarToggleExternalContent"
                                aria-controls="navbarToggleExternalContent"
                                aria-expanded="false"
                                aria-label="Toggle navigation"
                            >
                                <i class="bi bi-journals"></i> Documentos
                                <i class="bi bi-caret-down-fill"></i>
                            </a>
                            <div
                                class="pos-f-t collapse"
                                id="navbarToggleExternalContent"
                            >
                                <div class="bg-dark p-3">
                                    <li class="nav-item">
                                        <router-link
                                            to="/menu/documento"
                                            class="text-white nav-link margen tab_item"
                                        >
                                            <i
                                                class="bi bi-blockquote-left"
                                            ></i>
                                            Documentos Creados
                                        </router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link
                                            to="/menu/tipoDocumento"
                                            class="text-white nav-link margen tab_item"
                                        >
                                            <i
                                                class="bi bi-blockquote-left"
                                            ></i>
                                            Tipos Creados
                                        </router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link
                                            to="/menu/subtipoDocumento"
                                            class="text-white nav-link margen tab_item"
                                        >
                                            <i
                                                class="bi bi-blockquote-left"
                                            ></i>
                                            Sub Tipos Creados
                                        </router-link>
                                    </li>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item " v-if="id_rol == 1">
                            <router-link
                                to="/menu/tipoEvaluacion"
                                class="text-white nav-item nav-link tab_item"
                            >
                                <i class="bi bi-people-fill"></i> Tipos de
                                Evaluación
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <a
                                class="text-white nav-item nav-link dropdown-toggler tab_item"
                                data-toggle="collapse"
                                data-target="#Crearticket"
                                aria-controls="navbarToggleExternalContent"
                                aria-expanded="false"
                                aria-label="Toggle navigation"
                            >
                                <i class="bi bi-clipboard-plus"></i> Ticket
                                <i class="bi bi-caret-down-fill"></i>
                            </a>
                            <div class="pos-f-t collapse" id="Crearticket">
                                <div class="bg-dark p-3">
                                    <li class="nav-item">
                                        <router-link
                                            to="/menu/ticket"
                                            class="text-white nav-link margen tab_item"
                                        >
                                            <i
                                                class="bi bi-blockquote-left"
                                            ></i>
                                            Crear Ticket
                                        </router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link
                                            to="/menu/ticket/filtro-recibidos"
                                            class="text-white nav-link margen tab_item"
                                        >
                                            <i
                                                class="bi bi-blockquote-left"
                                            ></i>
                                            Tickets Recibidos
                                        </router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link
                                            to="/menu/ticket/filtro-enviados"
                                            class="text-white nav-link margen tab_item"
                                        >
                                            <i
                                                class="bi bi-blockquote-left"
                                            ></i>
                                            Tickets Enviados
                                        </router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link
                                            to="/menu/ticket/aprobar"
                                            class="text-white nav-link margen tab_item"
                                        >
                                            <i
                                                class="bi bi-blockquote-left"
                                            ></i>
                                            Tickets por Aprobar
                                        </router-link>
                                    </li>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <br />
        <div>
            <div class="row">
                <div class="col-12 col-md-12">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
        id_rol: '',
    }
  },
    methods: {
        getDocentes: function() {
            axios.get("/menu/home");
            this.id_rol= JSON.parse(window.localStorage.getItem('id_rol'));
        },
        salir: function() {

        axios.get('/salir').then((response)=>{
            localStorage.clear();
            location.href = "/";
        }).catch((err) => {
          console.log(err);
        });
        }
    },
    created: function() {
        this.getDocentes();
    },

    mounted() {
        this.getDocentes();
        $(".dropdown-toggle").dropdown();
    }
};
</script>
