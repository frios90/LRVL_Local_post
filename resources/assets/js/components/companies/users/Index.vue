<template >     
      <div class="content-wrapper">
        <div v-if="loader" class="loader"></div>
        
        <div class="d-flex div-navegate-page">    
          <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
          </router-link>
          <router-link to="/companies">               
            <p class="">empresas&nbsp;/&nbsp;</p>             
          </router-link>
          <p class="hover-cursor">usuarios</p>          
        </div>
        
        <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Gestión de usuarios para la empresa {{company.name}} </h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <router-link :to="{ name: 'companies.user.create' }">
                <img src="/intranet/img/icons/anadir.png" class="icon-action-module float-right" title="Presione para ir a la creación de un nuevo registro.">
              </router-link> 
              <router-link to="/companies">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link>
            </div>
          </div>  
        </div>                               
        <hr>
        <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
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
                <tr v-if="props.row.created_at != props.row.updated_at">
                  <td>Actualizada: </td> <td> {{props.row.updated_at}}</td>
                </tr>
                <tr v-if="props.row.deleted_at">
                  <td>Eliminada: </td> <td> {{props.row.deleted_at}}</td>
                </tr>
              </tbody>
            </table>        
          </span>
          <span slot="actions" slot-scope="props">
            <router-link v-if ="!props.row.deleted_at" :to="'/companies/'+company.id+'/users/edit/'+props.row.id">
              <img src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para ir a la configuración de las secciones y preguntas des cuestionario">
            </router-link>                  
            <span v-if="props.row.id != Auth.id">
              <img v-if ="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="presione para desactivar el registro" @click="changeStatus(props.row.id, '/user-status', '/users-table-list')" >
              <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para activar el registro"  @click="changeStatus(props.row.id, '/user-status', '/users-table-list')">
            </span>
          </span>                                                     
        </v-client-table>
            
      </div>          
</template>
<script>
    import Table from '../../mixins/Table.js'
    import Util  from '../../mixins/Util.js'
    export default {
      mixins: [Table, Util],
      data () {
        return {
          loader : false,
          company : {
            id : this.$route.params.company_id,
            name: ""
          }
        }
      },
      created () {
        this.getCompany()
        this.getDataTable('/users-company-table-list', {company_id: this.company.id})
          this.columns          =  ['rut', 'name',  'profile.label','dates', 'status','actions']
          this.options.headings = {   
              rut             : 'Rut',             
              name            : 'Nombre',
              'profile.label' : 'Perfil',
               dates      : 'Fechas',
              status      : 'Estado',  
              actions         : 'Acciones',
          }
        this.options.sortable = [
            'rut', 'name'             
        ]
      },
      methods: {
        getCompany () {
          var self = this
          this.loader = true
          this.$http.get('/get-company', {params: {id: self.company.id}})
          .then(response => {   
            self.loader = false                 
            self.company.name = response.body.name    
          }, response => {   
            self.loader = false               
            self.$toasted.global.APP_GENERAL_ERROR()
          })
        },   
        changeStatus(id, url, table) {
          this.loader = true
          var data = {
            _token : this.csrf,
            id     : id
          }
          var self = this
          this.$http.post(url, data)
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
          
        }       
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

