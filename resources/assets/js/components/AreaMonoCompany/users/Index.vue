<template >     
      <div class="content-wrapper">
        <div v-if="loader" class="loader"></div>
        
        <div class="d-flex div-navegate-page">    
          <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
          </router-link>
          <p class="hover-cursor">&nbsp;usuarios</p>          
        </div>
        
        <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Gestión de usuarios de la empresa</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <router-link :to="{ name: 'usercompany.create' }">
                <img src="/intranet/img/icons/anadir.png" class="icon-action-module float-right" title="Presione para ir a la creación de un nuevo registro.">
              </router-link>  
            </div>
          </div> 
        </div>                               
        <hr>
        <div class="grid-margin stretch-card">           
          <div class="">                    
            <div class="card-body">
              <v-client-table  class="index-table table-responsive-sm" :data="tableData" :columns="columns" :options="options">
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
                    <tr>
                      <td>Actualizada: </td> <td> {{props.row.updated_at}}</td>
                    </tr>
                    <tr v-if="props.row.deleted_at">
                      <td>Eliminada: </td> <td> {{props.row.deleted_at}}</td>
                    </tr>
                  </tbody>
                </table>        
              </span>
                <span slot="actions" slot-scope="props">
                  <router-link v-if ="!props.row.deleted_at" :to="'/users-company/edit/'+props.row.id">
                    <img src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Ver y editar los datos del usuario">
                  </router-link> 
                  <router-link v-if ="!props.row.deleted_at" :to="'/users-company/upload-photos/'+props.row.id">
                    <img src="/intranet/img/icons/camara-fotografica.png" class="hover-cursor icons-action-row-form" title="Ir a la configuración de imagenes del usuario">
                  </router-link>                             
                  <span v-if="props.row.id != Auth.id">
                    <img v-if ="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="presione para desactivar el registro" @click="changeStatus(props.row.id, '/user-status', '/users-company-table-list')" >
                    <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para activar el registro"  @click="changeStatus(props.row.id, '/user-status', '/users-company-table-list')">
                  </span>   </span>                                                     
              </v-client-table>
            </div> 
          </div>  
        </div>
      </div>          
</template>
<script>
    import Table from '../../mixins/Table.js'
    import Util  from '../../mixins/Util.js'
    export default {
      mixins: [Table, Util],
      data () {
        return {
        }
      },
      created () {
        this.getDataTable('/users-mono-company-table-list')
          this.columns          =  ['rut', 'name', 'profile.label', 'dates', 'status', 'actions']
          this.options.headings = {   
              rut             : 'Rut',             
              name            : 'Nombre',
              'profile.label' : 'Perfil',
              dates           : 'Fechas',
              status          : 'Estado',        
              actions         : 'Acciones',
          }
        this.options.sortable = [
            'rut', 'name'             
        ]
      },
      methods: {
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

