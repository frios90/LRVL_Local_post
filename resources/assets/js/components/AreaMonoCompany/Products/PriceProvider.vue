<template >
     <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>     

      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/mono-company-products">               
            <p class="text-muted mb-0 hover-cursor">&nbsp;productos&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">Configuración costo proveedores</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Configuración costo del producto [ {{ product.barcode }} ]  [ {{ product.name }} ] por proveedor </h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <router-link to="/mono-company-products">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link> 
            </div>
          </div> 
      </div>
      <hr>
      <div class="col-md-12">
          <div class="row form-basic">
            <div class="col-md-4">
              <label>Proveedor</label>
              <span class="mdi mdi-alert-circle circle-info" title="Seleccione el proveedor del producto para asignar un nuevo costo."></span>
              <select class="select-css" v-model="provider_cost.provider_id">
                <option value="">Seleccione un proveedor</option>
                <option v-for="(provider, key_providers) of providers" :key="key_providers" :value="provider.id">[ {{provider.rut}} ] {{provider.name}}</option>
              </select>
              <span class="errors" v-if="errors && errors.provider_id">{{ errors.provider_id[0] }}</span>               
            </div>
            <div class="col-md-4">
              <label>Costo</label>
              <span class="mdi mdi-alert-circle circle-info" title="Ingrese nuevo costo del proveedor seleccionado"></span>
              <input type="text" class="form-control form-control-sm" maxlength="9" @keypress="isNumber($event)" v-model="provider_cost.cost">       
              <span class="errors" v-if="errors && errors.cost">{{errors.cost[0] }}</span>             
            </div>   
            <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para guardar el nuevo costo" @click="postStoreCost()">
        
          </div>
        </div>
        <hr>               
      <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
          
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
                  <td>Deprecada: </td> <td> {{props.row.deleted_at}}</td>
                </tr>
              </tbody>
            </table>        
          </span>
          <span slot="rut_provider" slot-scope="props">
            {{props.row.provider.rut}} 
          </span>       
          <span slot="name_provider" slot-scope="props">
            {{props.row.provider.name}} 
          </span> 
          <span slot="cost" slot-scope="props">
            {{props.row.provider_price | currency}} 
          </span>
          <span slot="actions" slot-scope="props">
            <span v-if="props.row.deleted_at" class="span-historic-cost-status">Histórico</span>
            <span v-else class="span-current-cost-status">Actual</span>
          </span>  
      </v-client-table>
          
    </div>   
</template>
<script>
  import Table from '../../mixins/Table.js'
  import Util from '../../mixins/Util.js'
  export default {
    mixins: [Util, Table],
    data () {
      return {
        
        errors  : {},         
        product : {
          id             : this.$route.params.id,
          _token         : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          barcode        : "",
          name           : "",          
        },
        providers    : [],
        provider_cost : {
          product_id     : this.$route.params.id,
          _token         : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          provider_id    : "",
          cost           : "",
                  
        },

       
      }
    },       
    created() {       
      this.initData()

    },  
      
    methods: {   
      async initData () {
        await this.getData()
        await this.getProviders()
        this.loader = false
      },    
      getProviders () {
        var self = this
        this.$http.get('/get-providers-selector', {params: {barcode: this.barcode}})
          .then(response => { 
            self.providers = response.body
            console.log('self.providers')

            console.log(self.providers)
          }, response => {                 
            self.$toasted.global.APP_GENERAL_ERROR()
          })            
      }, 
      getData () {
        var self = this
        this.$http.get('/get-mono-company-products-provider', {params: {id: self.product.id}})
        .then(response => {      
          console.log(response.body)              
          self.product.name = response.body.name
          self.product.barcode = response.body.barcode
          console.log(response.body)
          self.getDirectDataTable(response.body.product_provider)
          self.columns          =  [ 'rut_provider', 'name_provider', 'cost', 'dates',  'actions']
          self.options.headings = {   
            rut_provider  : 'Rut proveedor',
            name_provider : 'Nombre proveedor',
            cost          : 'Costo',
            dates    : 'Fechas',
            actions  : "estado"
          }
          self.options.sortable = [
              'providers.name', 'providers.rut'             
          ]      
                  
        }, response => {                 
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      },
      postStoreCost () {
        event.preventDefault()
        var self = this  
        this.loader = true
        this.$http.post('/mono-company-products-provider-cost-store', this.provider_cost)
        .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            self.getData()
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
     
    },
    filters: {
      upper: function (value) {
        return value.toUpperCase();
      }
    }
  }
</script>
<style>
  .span-historic-cost-status {
    padding: 1px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(253, 120, 79) 0%, rgb(246, 219, 170) 100%);
    border-radius: 188px 188px 188px 188px;
    -moz-border-radius: 188px 188px 188px 188px;
    -webkit-border-radius: 188px 188px 188px 188px;

  }

  .span-current-cost-status {
    width:150px!important;
    padding: 1px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(79, 253, 102) 0%, rgba(170, 246, 193, 1) 100%);
    border-radius: 188px 188px 188px 188px;
    -moz-border-radius: 188px 188px 188px 188px;
    -webkit-border-radius: 188px 188px 188px 188px;

  }
</style>
@endsection
