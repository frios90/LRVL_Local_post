<template >     
      <div class="content-wrapper">
        <div v-if="loader" class="loader"></div>
        
        <div class="d-flex div-navegate-page">    
          <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
          </router-link>
          <p class="hover-cursor">&nbsp;categorías</p>          
        </div>
        
        <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Módulo gestión de categorías</h2>          
          </div>
          <div class="col-md-2">
            
          </div> 
        </div>                               
         <hr>     
        <form class="form-basic">       
            <div class="row">                
              <div class="form-group col-md-6">
                <label>Crear nueva categoría</label>
                <span class="mdi mdi-alert-circle circle-info" title="Ingrese el nombre de la nueva categoría"></span>
                <input type="text" class="form-control form-control-sm" v-model="category.label" name="label" maxlength="35" placeholder="Ingrese nueva categoría">
                <span class="errors" v-if="errors && errors.label">{{ errors.label[0] }}</span>               
              </div> 
              <div class="btn-main-view-action">
                <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para Guardar el nuevo registro." @click="postStore">
              </div>
            </div>      
        </form>
        <hr>
        <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
          <span slot="label" slot-scope="props">
            <input type="text" class="input-edit-category" v-model="props.row.label">
          </span>
          <span slot="status" slot-scope="props">
            <span v-if="!props.row.deleted_at" class="general-status-row-active">Activo</span>
            <span v-else class="general-status-row-inactive">Inactivo</span>
          </span>
          <span slot="actions" slot-scope="props">
            <img v-if ="!props.row.deleted_at" src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para editar el registro de categoría de producto" @click="postUpdate(props.row)">
            <router-link v-if ="!props.row.deleted_at" :to="'/categories/'+props.row.id+'/subcategories'">
              <img src="/intranet/img/icons/jerarquia.png" class="hover-cursor icons-action-row-form" title="Presione para ir al configuración de subcategorías del registro">
            </router-link>  
            <span v-if="!props.row.has_products">
              <img v-if="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="Presione para desactivar el registro" @click="changeStatus(props.row.id, '/category-status', '/categories-table-list')" >
              <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="Presione para activar el registro"  @click="changeStatus(props.row.id, '/category-status', '/categories-table-list')">
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
          errors    : {},
          category : {
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            label       : "" ,
            post_event : 'store'          
          }
        }
      },
      created () {
        this.getDataTable('/categories-table-list')
          this.columns          =  ['label', 'created_at', 'updated_at', 'deleted_at', 'status', 'actions']
          this.options.headings = { 
           
            label      : 'Nombre',
            created_at : 'Fecha de ingreso',
            updated_at : 'última actualización',
            deleted_at : 'Fecha de Baja', 
            actions    : 'Acciones',
            status : 'Estado'
          }
        this.options.sortable = [
            'label'             
        ]
      },
      methods: {
        changeStatus(id, url, table) {
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
          var self = this  
          this.loader = true
          await this.$http.post('/category-store', this.category)
          .then(response => {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.category.name = ""
              self.getDataTable('/categories-table-list')
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
        async postUpdate (category) {
          event.preventDefault()
          this.loader = true      
          var self = this  
          var send_category = {
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            label       : category.label,
            id         : category.id,
            post_event : 'update' 
          }
          await this.$http.post('/category-store', send_category)
          .then(response => {
              self.loader = false 
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.getDataTable('/categories-table-list')
            }, response => {   
                self.loader = false     
                self.getDataTable('/categories-table-list')          
                if (response.status === 422) {
                  self.$toasted.global.FORM_UNIC_FIELD()
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

