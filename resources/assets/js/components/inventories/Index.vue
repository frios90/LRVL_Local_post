<template >     
  <div class="content-wrapper">
    <div v-if="loader" class="loader"></div>
    <div class="d-flex div-navegate-page">    
      <router-link to="/dashboard">               
          <p class="">inicio&nbsp;/</p>             
      </router-link>
      <p class="text-muted mb-0 hover-cursor">&nbsp;inventarios</p>             
    </div> 
    <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Gestión de Inventarios</h2>          
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            <router-link :to="{ name: 'inventory.create' }">
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
              <br>
              <span v-if="props.row.inventory_status.code == 'CLOSED'" class="receptions-row-loaded">Cargada</span>
              <span v-else class="receptions-row-no-loaded">Sin cargar</span>
            </span>
            <span slot="dates" slot-scope="props">
              <table style="width: 100%;">
                <tbody>
                  <tr>
                    <td>Iniciada:</td><td>{{props.row.created_at}}</td>
                  </tr>
                  <tr v-if="props.row.created_at != props.row.updated_at && props.row.loaded_at != props.row.updated_at && props.row.deleted_at != props.row.updated_at" >
                    <td>Actualizada: </td> <td> {{props.row.updated_at}}</td>
                  </tr>
                  <tr v-if="props.row.loaded_at">
                    <td>Cargada: </td> <td> {{props.row.loaded_at}}</td>
                  </tr>
                  <tr v-if="props.row.deleted_at">
                    <td>Eliminada: </td> <td> {{props.row.deleted_at}}</td>
                  </tr>
                </tbody>
              </table>        
            </span>
      <span slot="actions" slot-scope="props">
        <router-link v-if ="!props.row.deleted_at && props.row.inventory_status.code != 'CLOSED'" :to="'/inventories/edit/'+props.row.id">
          <img src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para editar(continuar) el inventario">
        </router-link>
        <router-link v-if ="props.row.inventory_status.code == 'CLOSED'" :to="'/inventories/see/'+props.row.id">
          <img src="/intranet/img/icons/seleccione.png" class="hover-cursor icons-action-row-form" title="Presione para ver el detalle del inventario">
        </router-link>
        <img v-if ="!props.row.deleted_at && props.row.inventory_status.code != 'CLOSED'" src="/intranet/img/icons/subir.png" class="hover-cursor icons-action-row-form" title="Presione para cargar el inventario al maestro"  @click="chargeMaster(props.row.id)">
      <span v-if ="props.row.inventory_status.code != 'CLOSED'">
        <img v-if ="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="presione para desactivar el registro" @click="changeStatus(props.row.id)" >
        <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para activar el registro"  @click="changeStatus(props.row.id)">
      </span>
      </span>                                                     
    </v-client-table>
      
  </div>
</template>
<script>
    import Table from '../mixins/Table.js'
    import Util from '../mixins/Util.js'
    export default {
      mixins: [Table, Util],
      data () {
        return {
          loader : false,
        }
      },
      created () {
        this.getDataTable('/inventories-table-list')
        this.columns          =  ['id', 'user.name',  'dates', 'status',  'actions']
        this.options.headings = {                
          'id'        : 'Identificador',
          'user.name' : 'responsable',
          dates       : 'Fechas',
          status      : 'Estado',    
          actions     : 'Acciones',
        }
        this.options.sortable = [
            'guide'             
        ]
      },
      methods: {
        changeStatus(id) {
          this.loader = true
          var data = {
            _token : this.csrf,
            id     : id
          }
          var self = this
          this.$http.post('/inventory-status', data)
            .then(response => {
                self.getDataTable('/inventories-table-list')
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
        chargeMaster(id) {
         
          var data = {
            _token : this.csrf,
            id     : id
          }
          this.$swal({
            title              : '¿Esta seguro/a?',
            text               : 'Esta apunto de cargar los valores ingresados en el maestro definitivo de productos. Una vez procesado los datos no se pueden revertir los cambios',
            type               : 'warning',
            showCancelButton   : true,
            confirmButtonText  : 'Sí',
            cancelButtonText   : 'No',
            showCloseButton    : true
          }).then((result) => {
            if(result.value) {                      
              var self = this
               this.loader = true
              this.$http.post('/inventory_upload_master', data)
              .then(response => {                
                  self.$toasted.global.APP_GENERAL_SUCCESS()
                  self.loader = false
                  this.getDataTable('/inventories-table-list')
                  localStorage.removeItem("current_edit_inventory")
                  localStorage.removeItem("current_edit_detail_inventory")
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