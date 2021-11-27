<template >     
  <div class="content-wrapper">
    <div v-if="loader" class="loader"></div>
    <div class="d-flex div-navegate-page">    
      <router-link to="/dashboard">               
          <p class="">inicio&nbsp;/</p>             
      </router-link>
      <p class="text-muted mb-0 hover-cursor">&nbsp;recepciones</p>             
    </div>  

    <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Módulo de recepciones</h2>          
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            <router-link :to="{ name: 'reception.create' }">
              <img src="/intranet/img/icons/anadir.png" class="icon-action-module float-right" title="Presione para ir a la creación de un nuevo registro.">
            </router-link> 
          </div>
        </div> 
    </div>
    <hr>
    <div class="">   
                
        <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
            <span slot="status" slot-scope="props">
              <span v-if="!props.row.deleted_at" class="general-status-row-active">Activo</span>
              <span v-else class="general-status-row-inactive">Inactivo</span>
              <br>
              <span v-if="props.row.reception_status.code == 'CLOSED'" class="receptions-row-loaded">Cargada</span>
              <span v-else class="receptions-row-no-loaded">Sin cargar</span>
            </span>
           
            <span slot="dates" slot-scope="props">
              <table style="width: 100%;">
                <tbody>
                  <tr>
                    <td>Documento:</td><td>{{props.row.guide_date | dateInverted }}</td>
                  </tr>
                  <tr>
                    <td>Iniciada:</td><td>{{props.row.created_at}}</td>
                  </tr>
                  <tr v-if="props.row.created_at != props.row.updated_at && props.row.loaded_at != props.row.updated_at && props.row.deleted_at != props.row.updated_at">
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
            <router-link v-if ="!props.row.deleted_at && props.row.reception_status.code != 'CLOSED'" :to="'/receptions/edit/'+props.row.id">
              <img src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para editar(continuar) el recepción">
            </router-link>
            <router-link v-if ="props.row.reception_status.code == 'CLOSED'" :to="'/receptions/see/'+props.row.id">
              <img src="/intranet/img/icons/seleccione.png" class="hover-cursor icons-action-row-form" title="Presione para ver el detalle del recepción">
            </router-link>
            <img v-if ="!props.row.deleted_at && props.row.reception_status.code != 'CLOSED'" src="/intranet/img/icons/subir.png" class="hover-cursor icons-action-row-form" title="Presione para cargar el recepción al maestro"  @click="chargeMaster(props.row.id)">
      
            <span v-if ="props.row.reception_status.code != 'CLOSED'">
               <img v-if ="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="presione para desactivar el registro" @click="changeStatus(props.row.id)" >
               <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para activar el registro"  @click="changeStatus(props.row.id)">
            </span>
          </span>                                                     
        </v-client-table>
    </div>  
  </div>
</template>
<script>
    import Table from '../mixins/Table.js'
    import Util from '../mixins/Util.js'
    export default {
      mixins: [Table, Util],
      data () {
        return {
          loader : true,
        }
      },
      created () {
        this.getDataTable('/receptions-table-list')
        this.columns          =  ['guide', 'provider.name', 'user.name',  'dates', 'status',  'actions']
        this.options.headings = {   
           'provider.name': 'Proveedor',
           'user.name': 'Responsable',              
            guide      : 'Documento',
            dates      : 'Fechas',
            status      : 'Estado',                          
            actions    : 'Acciones',
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
          this.$http.post('/reception-status', data)
            .then(response => {
                self.getDataTable('/receptions-table-list')
                self.$toasted.global.APP_GENERAL_SUCCESS()
                self.loader = false
              }, response => {                 
                  if (response.status === 422) {
                    self.loader = false
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
              this.$http.post('/reception_upload_master', data)
              .then(response => {                
                  self.$toasted.global.APP_GENERAL_SUCCESS()
                  self.loader = false
                  this.getDataTable('/receptions-table-list')
                  localStorage.removeItem("current_edit_reception")
                  localStorage.removeItem("current_edit_detail_reception")
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
      }
    }
</script>
@endsection

