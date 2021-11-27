<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
       <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>       
        <p class="text-muted mb-0 hover-cursor">&nbsp;folios</p>             
      </div>  

      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Configuración de lotes de folios </h2>         
        </div>   
        <div class="col-md-2">
            <div class="btn-main-view-action">
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para guardar el nuevo lite de folios" @click="postStore">

            </div>
          </div>      
      </div> 
      <hr>       
      <form class="form-basic">       
          <div class="row">                
            <div class="form-group col-md-4">
                <label for="">Tipo </label>
                <select class="select-css" v-model="folio.type" name="type" @change="validateActiveFolio()">
                    <option value="0" selected>Seleccione un tipo</option>
                    <option v-for="(list, index) in list_selector" :key="index" :value="list.id">
                        {{ list.label }}
                    </option>
                </select>
                <span class="errors" v-if="errors && errors.type">{{errors.type[0] }}</span>
            </div>
              <div class="form-group col-md-4">
              <label>Desde</label>
              <span class="mdi mdi-alert-circle circle-info" title="Ingrese el primer número del lote"></span>
              <input type="text" class="form-control form-control-sm" v-model="folio.since" name="since" maxlength="9" @keypress="isNumber($event)">
              <span class="errors" v-if="errors && errors.since">{{ errors.since[0] }}</span>               
            </div> 
              <div class="form-group col-md-4">
              <label>Hasta</label>
              <span class="mdi mdi-alert-circle circle-info" title="Ingrese el último número del lote"></span>
              <input type="text" class="form-control form-control-sm" v-model="folio.until" name="until" maxlength="9" @keypress="isNumber($event)">
              <span class="errors" v-if="errors && errors.until">{{ errors.until[0] }}</span>               
            </div> 
          </div>      
      </form>

      <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
        <span slot="f_since" slot-scope="props" >                 
          {{props.row.since}}
        </span>
        
        <span slot="f_until" slot-scope="props">                 
          {{props.row.until}}
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
            <tr v-if="props.row.created_at != props.row.updated_at && props.row.created_at != props.row.deleted_at">
              <td>Actualizada: </td> <td> {{props.row.updated_at}}</td>
            </tr>
            <tr v-if="props.row.deleted_at">
              <td>Eliminada: </td> <td> {{props.row.deleted_at}}</td>
            </tr>
          </tbody>
        </table>        
      </span>
        <span slot="actions" slot-scope="props">                 
          <img v-if="!props.row.deleted_at && props.row.current == props.row.since" src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para guardar los cambios realizados en el registro" @click="postUpdate(props.row)">
          <span>
            <img v-if ="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="presione para desactivar el registro" @click="changeStatus(props.row.id, '/folio-status', '/folios-list-to-config')" >
            <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para activar el registro"  @click="changeStatus(props.row.id, '/folio-status', '/folios-list-to-config')">                
          </span>
        </span>
      </v-client-table>
      
    </div>   
</template>
<script>
  import Util from '../mixins/Util.js'
  import Table from '../mixins/Table.js'
  export default {
    mixins: [Util, Table],
      data () {
        return {
          errors : {},
          folios : [],
          folio: {
             _token    : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            type       : "",
            since      : "",
            until      : "",
            post_event : 'store'
          },
          list_selector: [],
          last_active_folio : {}
        }
      },
      created () {
        this.getDataTable('/folios-list-to-config')
        this.columns          =  ['document.label', 'f_since', 'current', 'f_until', 'dates', 'status', 'actions']
        this.options.headings = { 
          'status' :  'Estado',
          'document.label' : 'Tipo documento',
          f_since    : 'Desde',
          current    : 'Siguente',
          f_until    : 'hasta',
          dates      : 'Fechas',
          actions    : 'Acciones',
        }
        this.options.sortable = [
            'since', 'until'             
        ]
      },       
      mounted () {   
        this.getListSelector()    
      },
      methods: {        
        getListSelector () {
          var self = this
          this.$http.get('/get-code-list-for-type', {params : {type:"FOLIOS"}})
          .then(response => {  
              self.loader = false  
              self.list_selector = response.body                       
          }, response => {    
            self.loader = false             
              self.$toasted.global.APP_GENERAL_ERROR()
          })
        },
        validateActiveFolio () {
          if (this.folio.type == 0) {
            return
          }
          var self = this
          this.$http.get('/search-active-folio-for-type', {params : {type:this.folio.type}})
          .then(response => {    
              self.last_active_folio = response.body
              if (response.body){
                this.$swal({
                  title             : '¿Seguro?',
                  text              : 'Ya hay un lote de folios activos para el tipo de documento. Si continúas con el registro de este, se dará vigencia al nuevo lote cancelando el anterior. [Desde: '+response.body.since+'] - [Actual:'+response.body.current+'] - [Hasta: '+response.body.until+']',
                  type              : 'warning',
                  showCancelButton  : true,
                  confirmButtonText : 'Sí',
                  cancelButtonText  : 'No',
                  showCloseButton   : true
                }).then((result) => {
                  if(result.value) {
                    this.folio.since = parseInt(response.body.until) + 1
                  } else {
                    this.folio.type = ""                              }
                })
              } 
                                   
          }, response => {                 
              self.$toasted.global.APP_GENERAL_ERROR()
          })
        },
        postStore () {
          
          if (this.folio.since <= 0 || this.folio.until <= 0 ) {
              this.$swal.fire({
                icon: 'error',
                title: 'Error',
                text: '"Desde" y "Hasta" no pueden ser menores a 0',
                
              })
              return
          }

          if (this.folio.since >= this.folio.until ) {
              this.$swal.fire({
                icon: 'error',
                title: 'Error',
                text: '"Desde" debe ser menor que "Hasta"',
                
              })
              return
          }

          if (this.last_active_folio.type_folio_id == this.folio.type) {
            if (this.last_active_folio.since >= this.folio.since || this.last_active_folio.until >= this.folio.since) {
              this.$swal.fire({
                icon: 'error',
                title: 'Error',
                text: '"Desde" no puede estar en el rango de lotes ya declarados.',
                
              })
              return
            }
          }       

          event.preventDefault()
          this.loader = true            
          var self = this  
          this.$http.post('/folio-store', this.folio)
          .then(response => {
            self.loader = false
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              this.getDataTable('/folios-list-to-config')
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
     
        changeStatus(id, url, table) {
          this.loader = false
          var data = {
            _token : this.csrf,
            id     : id
          }
          var self = this
          this.loader = true
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
    }
    
  }
</script>
<style>

  .div-folio {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(5, 1fr);
    grid-column-gap: 0px;
    grid-row-gap: 0px;
    padding: 25px;
  }

  .div-folio-div1 { 
    grid-area: 1 / 1 / 4 / 4; 
    color: #fff;
    margin: auto auto!important;
  }
  .div-folio-div2 { 
    grid-area: 1 / 4 / 4 / 6; 
    color: #fff;
    font-size: 25px;
    margin: auto auto!important;  
  }
  .div-folio-div3 { 
    grid-area: 4 / 1 / 5 / 6; 
    color: #fff;
    margin: auto auto!important;  
  }
  .div-folio-div4 { 
    grid-area: 5 / 1 / 6 / 6; 
    color: #fff;
    margin: auto auto!important;  
  } 


  .div-folio-div5 { 
    grid-area: 1 / 6 / 6 / 7;
    
  } 

  .div-TICKET {
    background-color: teal; 
    margin: 1px;   
  }
  .div-BOLETA {
    background-color: teal;
    margin: 1px; 
  }
  .div-FACTURA {
    background-color: teal;
    margin: 1px; 
  }
</style>
@endsection
