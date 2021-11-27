<template >     
      <div class="content-wrapper">
        <div v-if="loader" class="loader"></div>
        
        <div class="d-flex div-navegate-page">    
          <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
          </router-link>
          <p class="hover-cursor">&nbsp;perfiles</p>          
        </div>
        
        <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Módulo Gestión de Perfiles</h2>          
          </div>
          <div class="col-md-2">
            
          </div> 
        </div>                               
        <hr>         
        <form class="form-basic">       
          <div class="row">                
            <div class="form-group col-md-6">
              <label>Crear nuevo perfil</label>
              <span class="mdi mdi-alert-circle circle-info" title="ingrese un nombre para el nuevo perfil a crear"></span>
              <input type="text" class="form-control form-control-sm" v-model="profile.label" name="label" placeholder="Ingrese el nombre del nuevo perfil" maxlength="35">
              <span class="errors" v-if="errors && errors.label">{{ errors.label[0] }}</span>               
            </div> 
            <div class="btn-main-view-action">
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para crear el nuevo perfil" @click="postStore">
            </div>
          </div>      
        </form>
        <hr>
        <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
          <span slot="label" slot-scope="props">
            <input type="text" class="input-edit-category" v-model="props.row.label" maxlength="35">
          </span>
         <span slot="status" slot-scope="props">
              <span v-if="!props.row.deleted_at" class="general-status-row-active">Activo</span>
              <span v-else class="general-status-row-inactive">Inactivo</span>
            </span>
            <span slot="dates" slot-scope="props">
            <table style="width: 100%;">
              <tbody>
                <tr>
                  <td>Creada</td><td>{{props.row.created_at}}</td>
                </tr>
                <tr v-if="props.row.created_at != props.row.updated_at && props.row.deleted_at != props.row.updated_at">
                  <td>Actualizada: </td> <td> {{props.row.updated_at}}</td>
                </tr>
                <tr v-if="props.row.deleted_at">
                  <td>Eliminada: </td> <td> {{props.row.deleted_at}}</td>
                </tr>
              </tbody>
            </table>        
          </span>
          <span slot="actions" slot-scope="props">                 
            <img v-if="!props.row.deleted_at" src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para ir a la configuración de las secciones y preguntas des cuestionario" @click="postUpdate(props.row)">
            <router-link v-if ="!props.row.deleted_at" :to="'/profiles/menus/'+props.row.id">
              <img src="/intranet/img/icons/jerarquia.png" class="hover-cursor icons-action-row-form" title="Presione para ir a la configuración de las secciones y preguntas des cuestionario">
            </router-link>
            <span v-if="!props.row.has_products">
              <img v-if="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="Presione para desactivar el registro" @click="changeStatus(props.row.id, '/profile-status', '/profiles-table-list')" >
              <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="Presione para activar el registro"  @click="changeStatus(props.row.id, '/profile-status', '/profiles-table-list')">
            </span>
            <span v-else>
              <img src="/intranet/img/icons/conexiones.png" class="hover-cursor icons-action-row-form" title="Existen productos asociados a este estado por lo que no se puede desactivar.">
            </span>
          </span>
        </v-client-table>
            
      </div>          
</template>
<script>
    import Table from '../mixins/Table.js'
    import Util  from '../mixins/Util.js'
    export default {
      mixins: [Table, Util],
      data () {
        return {
          errors : {},
           profile : {
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            label       : "" ,
            post_event : 'store'          
          }
        }
      },
      created () {
        this.getDataTable('/profiles-table-list')
        this.columns          =  ['label', 'dates', 'status', 'actions']
        this.options.headings = {   
          label  : 'Perfil',
          
          dates      : 'Fechas', 
          status : "Estado",
          actions    : 'Acciones',
        }
        this.options.sortable = [
            'label'             
        ]
      },
      methods: {
        async changeStatus(id, url, table) {
          this.loader = true
          var data = {
            _token : this.csrf,
            id     : id
          }
          var self = this
          await this.$http.post(url, data)
          .then(response => {
              self.getDataTable(table)
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.loader = false
            }, response => {
                self.loader = false
                if (response.status === 422) {
                  self.$toasted.global.APP_GENERAL_ERROR_FORM()
                  self.errors = response.body.errors
                }else{
                  self.$toasted.global.APP_GENERAL_ERROR()
                }
            })
        },           
        async postStore () {
          event.preventDefault()
          this.loader = true      
          var self = this  
          await this.$http.post('/profile-store', this.profile)
           .then(response => {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.getDataTable('/profiles-table-list')
              self.loader= false
            }, response => {    
              self.loader= false             
                if (response.status === 422) {
                  self.$toasted.global.APP_GENERAL_ERROR_FORM()
                  self.errors = response.body.errors
                }else{
                  self.$toasted.global.APP_GENERAL_ERROR()
                }
            })
          },
           async postUpdate (profile) {
            event.preventDefault()                
            var self = this  
            this.loader = true
            var send_profile  = {
              _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              label       : profile.label,
              id         : profile.id,
              post_event : 'update'          
            } 
            await this.$http.post('/profile-store', send_profile)
            .then(response => {              
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.loader = false
              self.getDataTable('/profiles-table-list')
            }, response => {            
              self.getDataTable('/profiles-table-list')
              self.loader = false
              if (response.status === 422) {
                self.$toasted.global.FORM_UNIC_FIELD()
              }
            })
          },    
      },
      filters: {
        upper: function (value) {
            return value.toUpperCase();
        }
      },
      computed: {
        rows() {
          return this.items.length
        }
      }
    }
</script>
@endsection

