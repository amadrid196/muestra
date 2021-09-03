import Vue from 'vue'
import Router from 'vue-router'


Vue.use(Router)
const home = Vue.component("home", () => import("./components/home.vue"));

const usuarios = Vue.component("usuarios", () => import("./components/usuarios/usuario"));
const usuarios_crear = Vue.component("usuarios-crear", () => import("./components/usuarios/usuario_crear"));
const perfil = Vue.component("perfil", () => import("./components/usuarios/perfil"));

const ticket = Vue.component("ticket", () => import("./components/tickets/ticket"));
const crear_ticket = Vue.component("crear-ticket", () => import("./components/tickets/ticket_crear"));
const recibidos = Vue.component("recibidos-ticket", () => import("./components/tickets/ticket_recibido"));
const enviados = Vue.component("enviados-ticket", () => import("./components/tickets/ticket_enviados"));
const filtro_recibidos = Vue.component("recibidos", () => import("./components/tickets/recibidos"));
const filtro_enviados = Vue.component("enviados", () => import("./components/tickets/enviados"));
const aprobar = Vue.component("aprobar", () => import("./components/tickets/aprobar"));
const ver_ticket = Vue.component("ver-ticket", () => import("./components/tickets/ticket_ver"));



const crear_estado = Vue.component("crear-estado", () => import("./components/estado/estado_crear.vue"));
const estado = Vue.component("estado", () => import("./components/estado/estado.vue"));

const crear_estado_registro = Vue.component("crear-estado-registro", () => import("./components/estado_registro_documento/estado_registro_crear.vue"));
const estado_registro = Vue.component("estado-registro", () => import("./components/estado_registro_documento/estado_registro.vue"));


const crear_rol = Vue.component("crear-rol", () => import("./components/roles/rol_crear.vue"));
const rol = Vue.component("rol", () => import("./components/roles/rol.vue"));


const departamento = Vue.component("departamento", () => import("./components/departamentos/departamento.vue"));
const crear_departamento = Vue.component("crear-departamento", () => import("./components/departamentos/departamento_crear.vue"));

const tipo_evaluacion = Vue.component("tipoEvaluacion", () => import("./components/evaluacion/tipo_evaluacion.vue"));
const crear_tipo_evaluacion = Vue.component("crear-tipoEvaluacion", () => import("./components/evaluacion/tipo_evaluacion_crear.vue"));

const tipo_estructura = Vue.component("tipoEstructura", () => import("./components/estructura/tipo_estructura.vue"));
const crear_tipo_estructura = Vue.component("crear-tipoEstructura", () => import("./components/estructura/tipo_estructura_crear.vue"));

const documento = Vue.component("documento", () => import("./components/documentos/documento.vue"));
const crear_documento = Vue.component("crear-documento", () => import("./components/documentos/documento_crear.vue"));

const crear_tipo_Documento = Vue.component("crear-tipoDocumento", () => import("./components/documentos/tipoDocumento_crear.vue"));
const tipo_documento = Vue.component("tipoDocumento", () => import("./components/documentos/tipoDocumento.vue"));

const crear_sub_tipo_Documento = Vue.component("crear-subtipoDocumento", () => import("./components/documentos/sub_tipo_Documento_crear.vue"));
const sub_tipo_documento = Vue.component("subtipoDocumento", () => import("./components/documentos/sub_tipo_Documento.vue"));

function beforeEnter(to, from, next){

    if(JSON.parse(window.localStorage.getItem('id_rol')) == 1){
        next();
    }else{
        next({ name: 'home' });
    }
}

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/menu',
            component: home,
            name : 'home',
        },
        {
            path: '/menu/usuarios',
            component: usuarios,
            name : 'usuarios',
            beforeEnter: beforeEnter
        },
        {
            path: '/menu/usuarios/usuarios-crear',
            component: usuarios_crear,
            name : 'usuarios-crear',
            beforeEnter: beforeEnter
        },
        {
            path: '/menu/usuario/perfil',
            component: perfil,
            name : 'perfil'
        },
        {
            path: '/menu/ticket',
            component: ticket,
            name : 'ticket',
        },
        {
            path: '/menu/ticket/ticket-crear',
            component: crear_ticket,
            name : 'crear-ticket'
        },
        {
            path: '/menu/ticket/ticket-ver',
            component: ver_ticket,
            name : 'ver-ticket'
        },
        {
            path: '/menu/ticket/ticket-recibidos',
            component: recibidos,
            name : 'recibidos'
        },
        {
            path: '/menu/ticket/filtro-recibidos',
            component: filtro_recibidos,
            name : 'filtro-recibidos'
        },
        {
            path: '/menu/ticket/ticket-enviados',
            component: enviados,
            name : 'enviados'
        },
        {
            path: '/menu/ticket/filtro-enviados',
            component: filtro_enviados,
            name : 'filtro-enviados'
        },
        {
            path: '/menu/ticket/aprobar',
            component: aprobar,
            name : 'aprobar'
        },
        {
            path: '/menu/estado/estado-crear',
            component: crear_estado,
            name : 'crear-estado',
            beforeEnter: beforeEnter
        },
        {
            path: '/menu/estado',
            component: estado,
            beforeEnter: beforeEnter
        },
        {
            path: '/menu/estado/estado-registro-crear',
            component: crear_estado_registro,
            name : 'crear-estado-registro',
            beforeEnter: beforeEnter
        },
        {
            path: '/menu/estado-registro',
            component: estado_registro,
            beforeEnter: beforeEnter
        },
        {
            path: '/menu/rol',
            component: rol,
            beforeEnter: beforeEnter
        },
        {
            path: '/menu/rol-crear',
            component: crear_rol,
            name : 'crear-rol',
            beforeEnter: beforeEnter
        },

        {
            path: '/menu/departamento-crear',
            component: crear_departamento,
            name : 'crear-departamento',
            beforeEnter: beforeEnter

        },
        {
            path: '/menu/departamento',
            component: departamento,
            beforeEnter: beforeEnter

        },

        {
            path: '/menu/tipoEvaluacion-crear',
            component: crear_tipo_evaluacion,
            name : 'crear-tipoEvaluacion',
            beforeEnter: beforeEnter

        },
        {
            path: '/menu/tipoEvaluacion',
            component: tipo_evaluacion,
            beforeEnter: beforeEnter

        },

        {
            path: '/menu/tipoEstructura-crear',
            component: crear_tipo_estructura,
            name : 'crear-tipoEstructura',
            beforeEnter: beforeEnter

        },
        {
            path: '/menu/tipoEstructura',
            component: tipo_estructura,
            beforeEnter: beforeEnter

        },
        {
            path: '/menu/documento-crear',
            component: crear_documento,
            name : 'crear-documento',
            beforeEnter: beforeEnter

        },
        {
            path: '/menu/documento',
            component: documento,
            beforeEnter: beforeEnter

        },
        {
            path: '/menu/tipoDocumento-crear',
            component: crear_tipo_Documento,
            name : 'crear-tipoDocumento',
            beforeEnter: beforeEnter

        },
        {
            path: '/menu/tipoDocumento',
            component: tipo_documento,
            beforeEnter: beforeEnter

        },

        {
            path: '/menu/subtipoDocumento-crear',
            component: crear_sub_tipo_Documento,
            name : 'crear-subtipoDocumento',
            beforeEnter: beforeEnter

        },
        {
            path: '/menu/subtipoDocumento',
            component: sub_tipo_documento,
            beforeEnter: beforeEnter

        },

        { path: '*',  component: home}
    ]
})
