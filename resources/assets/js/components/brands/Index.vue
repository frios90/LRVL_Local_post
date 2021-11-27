<template >     
  <div class="content-wrapper">
    <div v-if="loader" class="loader"></div>    
    <div class="d-flex div-navegate-page">    
      <router-link to="/dashboard">               
        <p class="">inicio&nbsp;/</p>             
      </router-link>
      <p class="hover-cursor">&nbsp;marcas</p>          
    </div>        
    <div class="row div-title-module">
      <div class="col-md-10">
        <h2>Gestión de marcas de productos</h2>          
      </div>
      <div class="col-md-2">       
      </div> 
    </div>    
    <hr>             
    <form class="form-basic">       
        <div class="row">                
          <div class="form-group col-md-6">
            <label>Crear nueva marca</label>
            <span class="mdi mdi-alert-circle circle-info" title="Para añadir una nueva marca ingrese su nombre"></span>
            <input type="text" class="form-control form-control-sm" v-model="brand.label" name="label" maxlength="35" placeholder="Ingrese el nombre de la marca" >
            <span class="errors" v-if="errors && errors.label">{{ errors.label[0] }}</span>               
          </div> 
          <div class="btn-main-view-action">
            <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para guardar el nuevo registro de Marcas" @click="postStore">
          </div>
        </div>      
    </form>
    <hr>
    <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
     
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
      <span slot="label" slot-scope="props">        
        <input type="text" class="input-edit-category" maxlength="35" v-model="props.row.label">
      </span>
      <span slot="status" slot-scope="props">
        <span v-if="!props.row.deleted_at" class="general-status-row-active">Activo</span>
        <span v-else class="general-status-row-inactive">Inactivo</span>
      </span>
      <span slot="actions" slot-scope="props">
        <img v-if ="!props.row.deleted_at" src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para editar el registro de Marca de producto" @click="postUpdate(props.row)">                 
       <span v-if="!props.row.has_products">
          <img v-if="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="Presione para desactivar el registro" @click="changeStatus(props.row.id, '/brand-status', '/brands-table-list')" >
          <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="Presione para activar el registro"  @click="changeStatus(props.row.id, '/brand-status', '/brands-table-list')">
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
          brand : {
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            label       : "" ,
            post_event : 'store'          
          }
        }
      },
      created () {
        this.getDataTable('/brands-table-list')
          this.columns          =  [ 'label', 'dates', 'status', 'actions']
          this.options.headings = {   
            dates      : 'Fechas',
            status     : 'Estado',
            label      : 'Marca',
            actions    : 'Acciones',
          }
        this.options.sortable = [
            'label'             
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
                  if (response.status === 422) {
                    self.$toasted.global.APP_GENERAL_ERROR_FORM()
                    self.errors = response.body.errors
                  }else{
                    self.$toasted.global.APP_GENERAL_ERROR()
                  }
              })
          
        },
        postStore () {
          event.preventDefault()   
          this.loader = true   
          var self = this  
          this.$http.post('/brand-store', this.brand)
          .then(response => {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.loader = false
              this.getDataTable('/brands-table-list')
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
        postUpdate (brand) {
          event.preventDefault()
          this.loader = true                  
          var self = this  
          var send_brand  = {
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            label       : brand.label,
            id         : brand.id,
            post_event : 'update'          
          }
          this.$http.post('/brand-store', send_brand)
          .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            self.loader = false
            self.getDataTable('/brands-table-list')
          }, response => {    
            this.getDataTable('/brands-table-list')  
            self.loader = false           
            if (response.status === 422) {
              self.$toasted.global.FORM_UNIC_FIELD()
            }
          })
        }
      }
    }
</script>
<style>
 

</style>
@endsection

