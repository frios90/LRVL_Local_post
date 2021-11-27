<template >     
  <div class="content-wrapper">
    <div v-if="loader" class="loader"></div>
    <div class="d-flex div-navegate-page">    
      <router-link to="/dashboard">               
          <p class="">inicio&nbsp;/</p>             
      </router-link>
      <p class="text-muted mb-0 hover-cursor">&nbsp;ventas</p>             
    </div>  

    <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Gestión de ventas</h2>          
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">            
            <router-link to="/ticket-register">
              <img v-if="Auth.profile.code == 'ADMIN' || Auth.profile.code == 'SUDO'" src="/intranet/img/icons/factura.png" class="icon-action-module float-right"  title="Presione para ir a la emisión de ticket.">
            </router-link> 
          </div>
        </div> 
    </div>        
   <hr>
    <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
     
     
      <span slot="total" slot-scope="props">
        <span>
            {{props.row.total | currency}}
        </span>
      </span>
      <span slot="user" slot-scope="props">
        <span>
            {{props.row.user.name}}
        </span>
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
            
            <tr v-if="props.row.deleted_at">
              <td>Eliminada: </td> <td> {{props.row.deleted_at}}</td>
            </tr>
          </tbody>
        </table>        
      </span>      
      <span slot="actions" slot-scope="props">   
        <router-link  :to="'/sales/see/'+props.row.id">
          <img src="/intranet/img/icons/buscar.png" class="hover-cursor icons-action-row-form" title="Ver el detalle del ticket">
        </router-link>      
        <span v-if="props.row.code.code == 'QUOTATION'">
          <a :href="'/sale-pdf-quotation/'+props.row.id"> <img src="/intranet/img/icons/descargar.png" class="hover-cursor icons-action-row-form" title="Presione exportar PDF de la cotización."></a>
        </span> 
        <span v-if="props.row.step.code == 'CANCEL' || props.row.step.code == 'PENDING'">
          <img v-if ="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="Presione para cancelar lo realizado" @click="changeStatus(props.row.id)" >
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
        }
      },
      created () {
        this.getDataTable('/sales-table-list')
        this.columns          =  ['number', 'user', 'dates',  'total', 'status', 'actions']
        this.options.headings = {                
            number     : 'Folio', 
            base       : 'neto', 
            iva        : 'iva', 
            user       : 'Responsable',
            
            dates      : 'Fechas',
            actions    : 'Acciones',
            doc_leter : "Doc.",
            status   : 'Estado'

            
        }
        this.options.sortable = [
            'number'            
        ]
      },
      methods: {
        changeStatus(id) {
          this.loader = false
          var data = {
            _token : this.csrf,
            id     : id
          }
          var self = this
          this.loader = true
          this.$http.post('/sale-status', data)
            .then(response => {
                self.getDataTable('/sales-table-list')
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
     
      }
    }
</script>
<style>
  .span-QUOTATION {
    width:70%;
    padding: 1px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(253, 120, 79) 0%, rgba(170, 246, 193, 1) 100%);
    border-radius: 188px 188px 188px 188px;
    -moz-border-radius: 188px 188px 188px 188px;
    -webkit-border-radius: 188px 188px 188px 188px;
  }

  .span-SALE {
    width:150px!important;
    padding: 1px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(79, 120, 253) 0%, rgba(170, 246, 193, 1) 100%);
    border-radius: 188px 188px 188px 188px;
    -moz-border-radius: 188px 188px 188px 188px;
    -webkit-border-radius: 188px 188px 188px 188px;
  }
</style>
@endsection