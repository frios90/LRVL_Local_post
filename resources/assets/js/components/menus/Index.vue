<template >     
      <div class="content-wrapper">
        <div v-if="loader" class="loader"></div>
        
        <div class="d-flex div-navegate-page">    
          <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
          </router-link>
          <p class="hover-cursor">&nbsp;menus</p>          
        </div>
        
        <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Módulo Gestión de Menus</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <router-link :to="{ name: 'menu.create' }">
                <img src="/intranet/img/icons/anadir.png" class="icon-action-module float-right" title="Presione para ir a la creación de un nuevo registro.">
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
          <span slot="actions" slot-scope="props">
            <router-link v-if ="!props.row.deleted_at" :to="'/menus/edit/'+props.row.id">
              <img src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para ir al detalle y edición del menu">
            </router-link>
            
            <img v-if ="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="Presione para desactivar el registro" @click="changeStatus(props.row.id, '/menu-status', '/menus-table-list')" >
            <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="Presione para activar el registro"  @click="changeStatus(props.row.id, '/menu-status', '/menus-table-list')">
          
            <img src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="Presione para eliminar definitivamente el menu"  @click="truncate(props.row.id, '/menu-truncate', '/menus-table-list')">

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
          loader : true,
        }
      },
      created () {
        this.getDataTable('/menus-table-list')
          this.columns          =  ['name', 'route', 'parent_menu.name', 'status', 'actions']
          this.options.headings = {   
            name               : 'Títule del menu',
            route              : 'Ruta',
            status             : 'Estado',
            'parent_menu.name' : 'Menu',
            actions            : 'Acciones',
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
          
        },   
        truncate(id, url, table) {
           this.$swal({
              title             : '¿Esta seguro/a?',
              text              : 'Esta apunto de eliminar definitivamente el menu.',
              type              : 'warning',
              showCancelButton  : true,
              confirmButtonText : 'Sí',
              cancelButtonText  : 'No',
              showCloseButton   : true
            }).then((result) => {
              if(result.value) {
                self.loader = true 
                this.loader = false
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

