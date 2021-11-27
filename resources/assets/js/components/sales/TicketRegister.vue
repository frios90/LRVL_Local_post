<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>               
      <div class="row">  
        <div class="col-md-12">            
          <div class="row">
            <div class="col-md-12 actions-view-ticket">  
              <div class="row div-title-module">
                <div class="col-md-10">
                  <h2>Nuevo ticket de Ventas</h2>
                </div> 
                <div class="col-md-2">
                    <div class="btn-main-view-action">                     
                      <router-link to="/sales">
                        <img src="/intranet/img/icons/reembolso.png" 
                            class="icon-action-module float-right"  
                            title="Presione para ir al listado de ventas.">
                      </router-link> 
                    </div>
                  </div>  
              </div> 
              <hr>           
            </div>            
          </div>
          <div class="row">
            <div class="col-md-4"> 
              <input placeholder="Escanear un Código de Barras" 
                    autofocus type="text" 
                    class="autocomplete-input" 
                    v-model="barcode" 
                    name="barcode" 
                    @keyup="addItem()">  
              <span class="add_product_ticket_error" v-if="add_error">{{ add_error }}</span> 
            </div> 
            <div class="col-md-4">
                <autocomplete
                  id="id-auto-search-product"
                  :search="getProductsToSearchList"
                  placeholder="Buscar por código o nombre"
                  aria-label="Buscar Producto"
                  @submit="selectionProductAutoSearch"
                ></autocomplete>   
            </div>
            <div class="col-md-4">
              <table style="width: 100%">
                  <tbody class="table-bordered">
                    <tr>
                      <td class="td-label-ticket">
                        Ticket 
                      </td>
                      <td class="td-value-ticket">{{current_folio.current}}</td>
                    </tr>
                    <tr>
                      <td class="td-label-base">Total Base</td>
                      <td class="td-value-iva">{{totals.base | currency}}</td>
                    </tr>
                    <tr>
                      <td class="td-label-iva">Total Iva</td>
                      <td class="td-value-base">{{totals.iva | currency}}</td>
                    </tr>
                    <tr>
                      <td class="td-label-total">Total a Pago</td>
                      <td class="td-value-total">{{totals.total | currency}}</td>
                    </tr>
                    <tr>
                      <td class="td-label-total">Emitir Documento</td>
                      <td v-if="product_list.length > 0" class="btn-approve-item" style="cursor: pointer" >
                        <span @click="storeTicket()">
                            Aceptar Emisión
                        </span>
                        
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
          </div>
          <div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-receptions table-bordered">
                  <thead>
                    <th style="width: 5%!important; text-align: center!important"></th>
                    <th style="width: 10%!important; text-align: center!important">Código</th>
                    <th style="width: 5%!important; text-align: center!important">Disponible</th>
                    <th style="width: 30%!important; text-align: left!important;">Descripción</th>
                    <th style="width: 15%!important; text-align: center!important">Cantidad</th>
                    <th style="width: 15%!important; text-align: center!important">$ Unidad</th>
                    <th style="width: 15%!important; text-align: center!important">$ Total</th>
                  </thead> 
                  <tbody>
                    <tr v-for="(item, key_item) of product_list" :key="key_item" class="row-item-sale">
                      <td style="width: 5%!important ; text-align: left!important">
                        <img src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para eliminar el item"  @click="deleteItem(key_item)">
                      </td>
                      <td style="width: 10%!important; text-align: left!important; font-size: 10px">{{item.barcode}}</td>
                      <td style="width: 5%!important ; text-align: center!important; font-size: 11px">{{item.stock_able}}</td>
                      <td style="width: 30%!important ; text-align: left!important; font-size: 11px">{{item.name}}</td>
                      <td style="width: 15%!important; text-align: center!important"> <input  type="number" class="reception-number-sale"  v-model="item.qty" @change="changeQtyInput(item, key_item)"  @keyup="changeQtyInput(item, key_item)"> </td>
                      <td style="width: 15%!important ; text-align: center!important">{{item.base_price | currency}}</td>
                      <td style="width: 15%!important ; text-align: center!important">{{item.total_price | currency}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <hr>
          
        </div>
      </div> 
      <modal name="modal-add-customer" :width="750" :height="600" :adaptive="true" :resizable="true" :draggable="true" @before-open="beforeOpenCustomerModal">
        <div class="modal-body ">
          <div class="modal-content">         
            
            <div class="row div-title-module">
              <div class="col-md-10">
                <h2 class="h2-add-customer">Registrar datos de un nuevo cliente</h2>          
              </div>
              <div class="col-md-2">
                  <div class="btn-main-view-action">
                    <span><img src="/intranet/img/icons/comprobar.png" alt="" class="icon-action-module float-right" @click="storeCustomer()"></span>  
                  </div>
              </div> 
            </div>
            
            <div class="scroll-to-modal-body-customer">
              <hr>
              <form class="form-modal-customer-ticket">  
                <table class="table-add-customer">
                  <tbody>
                    <tr>
                      <td class="td-key">Rut</td>
                      <td class="td-value">
                        <input type="text" class="form-control" v-model="customer.rut" name="rut" v-rut:live maxlength="12" >  
                        <span class="errors" v-if="errors && errors.rut">{{ errors.rut[0] }}</span>    
                      </td>  
                    </tr> 
                    <tr>
                      <td class="td-key">Nombre</td>
                      <td class="td-value">
                        <input type="text" class="form-control" v-model="customer.name" name="name" >
                        <span class="errors" v-if="errors && errors.name">{{ errors.name[0] }}</span>          
                      </td>  
                    </tr> 
                    <tr>
                      <td class="td-key">Teléfono</td>
                      <td class="td-value">
                        <input type="text" class="form-control" v-model="customer.phone" name="phone" maxlength="9" @keypress="isNumber($event)">       
                        <span class="errors" v-if="errors && errors.phone">{{errors.phone[0] }}</span>        
                      </td>  
                    </tr> 
                    <tr>
                      <td class="td-key">Correo</td>
                      <td class="td-value">
                        <input type="text" class="form-control" v-model="customer.email" name="email" >       
                        <span class="errors" v-if="errors && errors.email">{{errors.email[0] }}</span>     
                      </td>  
                    </tr> 
                    <tr>
                      <td class="td-key">Región</td>
                      <td class="td-value">
                        <multiselect 
                          v-model="customer.region_id" 
                          deselect-label="" 
                          selected-label="" 
                          selectLabel=""
                          track-by="name" 
                          label="name" 
                          noOptions="Sin resultados"
                          placeholder="" 
                          :options="region_list" 
                          :searchable="true" 
                          :allow-empty="false"
                          @input="getCommunes()"

                        >
                        <template slot="singleLabel" slot-scope="{ option }">
                          <strong>{{ option.name }}</strong>
                        </template>
                        <template v-slot:noOptions>
                            No hay resultados.
                        </template>
                        <template v-slot:noResult>
                            No hay resultados.
                        </template>
                      </multiselect>  
                      <span class="errors" v-if="errors && errors.region_id">{{errors.region_id[0] }}</span>
                      </td>
                    </tr> 
                    <tr>
                      <td class="td-key">Comuna</td>
                      <td class="td-value">
                        <multiselect 
                          v-model="customer.commune_id" 
                          deselect-label="" 
                          selected-label="" 
                          selectLabel=""
                          track-by="name" 
                          label="name"                      
                          placeholder="" 
                          :options="commune_list"                       
                          :searchable="true" 
                          :allow-empty="false"
                        >
                        <template slot="singleLabel" slot-scope="{ option }">
                          <strong>{{ option.name }}</strong>
                        </template>
                        <template v-slot:noOptions>
                            No hay resultados.
                        </template>
                        <template v-slot:noResult>
                            No hay resultados.
                        </template>
                      </multiselect>  
                      <span class="errors" v-if="errors && errors.commune_id">{{errors.commune_id[0] }}</span>
                      </td>
                    </tr> 
                    <tr>
                      <td class="td-key">Dirección</td>
                      <td class="td-value">
                        <input type="text" class="form-control" v-model="customer.address" name="address" >       
                        <span class="errors" v-if="errors && errors.address">{{errors.address[0] }}</span>   
                      </td>
                    </tr>                     
                  </tbody>  
                </table>     
              </form>    
            </div>
          </div>
        </div>
      </modal>  
    </div>   
</template>
<script>
  import Util from '../mixins/Util.js'
  import Autocomplete from '@trevoreyre/autocomplete-vue'
  import '@trevoreyre/autocomplete-vue/dist/style.css'
  export default {
    mixins: [Util],
    components: {
      Autocomplete
    },  
    data () {
      return {
        errors       : {},   
        _token       : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),       
        barcode      : '',
        last_product : '',
        product_list : [], 
        totals  : {
          base  : 0,
          iva   : 0,
          total : 0        
        },
        selected_type: "",
        products_to_search_list  : [],
        customers_to_search_list : [],
        customer : {
          _token : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),   
          rut: '',
          name: '',
          address: '',
          region_id: '',
          commune_id: '',
          phone: '',
          email: '',
          post_event: 'store',
        },
        selected_customer : {
          id: '',
          rut: '',
          name: '',
          address: '',
          region_id: '',
          commune_id: '',
          phone: '',
          email: '',
        },
        region_list: [],
        commune_list: [],
        current_folio: "",
        add_error: "",
        ticket_types: []
      }
    },
    created () {
      this.initData()  
    
    },
    methods: {
      async initData () {
        await this.getRegions()
        await this.getCurrentFolio()
        this.loader = false
      },    
     
      
      beforeOpenCustomerModal () {
        this.customer = {
          _token : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          rut: '',
          name: '',
          address: '',
          region_id: '',
          commune_id: '',
          phone: '',
          email: '',
        }
      },
      getRegions() {
        var self = this
        this.$http.get('/region-list').then(function(response) {
            self.region_list = response.body
            self.region_list.forEach(function(val, ind){
              if (val.id ==  self.customer.region_id) {
                  self.customer.region_id = val
              }              
            })  
        }, function() {
            this.$toasted.global.APP_GENERAR_ERROR()
        })
      },      
      getCommunes() {
        this.commune_id = ''
        var self = this
        this.$http.get('/commune-list', {
            params: {
                region: self.customer.region_id
            }
        }).then(function(response) {
            self.commune_list = response.body
            self.commune_list.forEach(function(val, ind){
              if (val.id ==  self.customer.commune_id) {
                self.customer.commune_id = val
              }
            })   
        }, function() {
            this.$toasted.global.APP_GENERAR_ERROR()
        })
      },       
      hideAddCustomer() {
        this.$modal.hide("modal-add-customer")
      },
      selectionProductAutoSearch (result) {
       
        if (result && window.event.keyCode !== 13) {
          var result_split = result.split('-')
          this.barcode = result_split[0]
          this.addItem(true)          
        }        
      },
     
      async getProductsToSearchList (search) {
        if (search.length < 1) {
        return [] }
        var send = {
          value: search
        }        
        var self = this
        await this.$http.get('/company-products-to-search-list', {params: send})
              .then(response => {    
                  self.products_to_search_list = response.body                
              }, response => {                 
                  self.$toasted.global.APP_GENERAL_ERROR()
              })            
              return this.products_to_search_list
      },
      async getCustomersToSearchList (search) {
        if (search.length < 1) {
        return [] }
        var send = {
          value: search
        }        
        var self = this
        await this.$http.get('/customers-to-search-list', {params: send})
              .then(response => {    
                  self.products_to_search_list = response.body                
              }, response => {                 
                  self.$toasted.global.APP_GENERAL_ERROR()
              })            
              return this.products_to_search_list
      },
      async addItem (auto=false) {
        window.event.preventDefault()
        if(auto) {
          window.event.keyCode = 13
        }
        this.add_error = ""
        if (window.event.keyCode === 13) {
          var self =  this
          var has  = false
          this.product_list.forEach(function (v, i) {
            if(v.barcode == self.barcode) {     
              if (self.product_list[i].qty >= self.product_list[i].stock_able) {
                self.add_error = "La cantidad ingresada a superado el stock disponible"
              } else {
                self.product_list[i].qty = parseInt(self.product_list[i].qty) + 1
                self.product_list[i].total_price = self.product_list[i].qty * self.product_list[i].base_price              
                self.last_product = self.product_list[i]
               
              }
              has         = true
              self.barcode = ''              
            }
          })
          if (!has) {  
                      
            await this.$http.get('/get-product-to-add-sale', {params: {barcode: this.barcode}})
            .then(response => { 
              console.log(response.body.stock_able)
              if (response.body.stock_able < 1) {
                self.add_error = "El producto " +response.body.name+ " no cuenta con stock disponible"
              } else if (response.body.base_price < 1) {
                self.add_error = "El producto " +response.body.name+ " no tiene precio configurado"
              } else if (!response.body) {
                self.add_error = "El producto " +self.barcode+ " no existe"
              } else {
                self.add_error = ""
                self.last_product = response.body
                self.product_list.push(self.last_product)                 
              }               
                
              self.barcode = ''
                
            }, response => {                 
                self.$toasted.global.APP_GENERAL_ERROR()
            })
          }
          this.caculateTotals()
        }          
      },
      async caculateTotals () {
        var tmp_total = {
          base  : 0, 
          iva   : 0,
          total : 0
        }
        this.product_list.forEach(function (val, ind) {
            tmp_total.base  = parseInt(tmp_total.base) + (parseInt(val.base_price) * parseInt(val.qty))
            tmp_total.iva   = (parseInt(tmp_total.base) / 100 ) * 19
            tmp_total.total = parseInt(tmp_total.base) + parseInt(tmp_total.iva)
        })
        this.totals = tmp_total
      },
      deleteItem (key_item) {
        this.$swal({
          title             : '¿Esta seguro/a?',
          text              : 'Esta apunto de eliminar el registro contado.',
          type              : 'warning',
          showCancelButton  : true,
          confirmButtonText : 'Sí',
          cancelButtonText  : 'No',
          showCloseButton   : true
        }).then((result) => {
          if(result.value) {                      
            this.product_list.splice(key_item, 1)     
          } 
        })                     
      },
      changeQtyInput (product, key) {
        if (parseInt(product.qty) < 1) {
          this.product_list.splice(key, 1)
          this.caculateTotals() 
          return
        }
        if (parseInt(product.qty) > parseInt(product.stock_able)) {
          product.qty = product.stock_able
        } 
        this.product_list[key].total_price = parseInt(product.qty) * parseInt(product.base_price)
        this.caculateTotals()        
      }, 
    
      async getCurrentFolio () {
        var send = {
          type: 'TICKET'
        }
        var self = this
        await this.$http.get('/get-current-folio', {params: send})
        .then(response => {    
            self.current_folio = response.body                
        }, response => {                 
            self.$toasted.global.APP_GENERAL_ERROR()
        })            
        return this.products_to_search_list

      },
      async storeTicket () {
         window.event.preventDefault()
         this.loader = true  
          var send = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            // selected_customer : this.selected_customer.id,
            totals            : this.totals,
            product_list      : this.product_list,
            current_folio     : this.current_folio,
            type_ticket       : this.selected_type,
          }          
          
          var self = this  
          this.$http.post('/store-ticket', send)
          .then(response => {
              self.loader = false
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.reset()    
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
      reset () {
        this.getCurrentFolio()  
        this.barcode                      = ''
        this.last_product                 = ''
        this.product_list                 = []         
        this.totals.base                  = 0
        this.totals.iva                   = 0
        this.totals.total                 = 0        
        this.selected_type                = ""
        this.products_to_search_list      = []
        this.customers_to_search_list     = []
        this.customer.rut                 = ''
        this.customer.name                = ''
        this.customer.address             = ''
        this.customer.region_id           = ''
        this.customer.commune_id          = ''
        this.customer.phone               = ''
        this.customer.email               = ''
        this.selected_customer.id         = ''
        this.selected_customer.rut        = ''
        this.selected_customer.name       = ''
        this.selected_customer.address    = ''
        this.selected_customer.region_id  = ''
        this.selected_customer.commune_id = ''
        this.selected_customer.phone      = ''
        this.selected_customer.email      = ''
        
      }
    }
  }
</script>
<style>
  .row-item-sale td{
    padding: 0px!important;
  }

  .box-search-code {
    background: rgb(148, 160, 255);
    margin-bottom: 5px;
    padding: 25px;
  }

  .add_product_ticket_error {
    color: red;
    font-size: 10px;
  }

  .td-label-ticket {
    width: 175px;
    padding: 5px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(148, 160, 255);
  }

  .td-label-base {
    width: 175px;
    padding: 5px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(148, 160, 255);
  }

  .td-label-iva {
    width: 175px;
    padding: 5px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(148, 160, 255);
  }

  .td-label-total {
    width: 175px;
    padding: 5px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(148, 160, 255);
  }

  .td-value-ticket {
    padding: 5px 5px 5px 25px;
    color: tomato;
    text-align: right!important;
    font-weight: 600;
    font-size: 28px;
  }

  .td-value-base {
    padding: 5px 5px 5px 25px;
    color: rgba(29, 51, 68, 1);
    text-align: right!important;
    font-size: 14px!important;
  }

  .td-value-iva {
    padding: 5px 5px 5px 25px;
    color: rgba(29, 51, 68, 1);
    text-align: right!important;
    font-size: 14px!important;
  }

  .td-value-total {
    padding: 5px 5px 5px 25px;
    color: rgba(29, 51, 68, 1);
    text-align: right!important;
    font-size: 14px!important;
  }

  .td-value-emit {
    cursor: pointer;
    padding: 5px 5px 5px 25px;
    color: #fff;
    background: rgb(148, 160, 255);
    background: linear-gradient(90deg,  rgba(170, 246, 193, 1) 0%,rgba(29, 51, 68, 1) 100%);
    text-align: center!important;
  }

   .td-value-emit:hover {
    transition: 0.5s;
    -webkit-transform:scale(1.1);transform:scale(1.1);
  }

  .reception-number-sale {
    text-align: center;
    font-size: 15px;
    width: 80px;
  }

  .btn-action-top {
    color: #fff;
    font-size: 12px;
    padding: 12px;
    background-color: teal;
  }

  .div-barcode-enter {
    text-align: center;
    margin-top: 50px;
  }

  .actions-view-ticket {
    padding-left: 50px;
  }

  .autocomplete-input {
    border: 0px!important;
    border-bottom: solid 1px #3cbc64!important;
    border-radius: 15px!important;
    color: #3cbc64!important;
    font-size: 12px;
    background-color: #fff;
  }

  .autocomplete-result-list li {
    font-size: 12px;
  }

  .autocomplete-result-list li:hover {
    color: #fff;
    transition: .5s;
    background: rgb(97, 153, 165);
    background: -webkit-linear-gradient(45deg, rgb(97, 153, 165), rgba(170, 246, 193, 1));
    background: -o-linear-gradient(45deg, rgb(97, 153, 165), rgba(170, 246, 193, 1));
    background: -moz-linear-gradient(45deg, rgb(97, 153, 165), rgba(170, 246, 193, 1));
    background: linear-gradient(45deg, rgb(97, 153, 165), rgba(170, 246, 193, 1));
  }

  .box-scan-product {
    margin: 30px auto 15px;
  } 

  .form-modal-customer-ticket {
    padding:25px;
  }

  .select-modal-customer {
    font-size: 12px;
    font-weight: 400;
    color: #444;
    line-height: 1.3;
    padding: 0px;
    width: 95%;
    max-width: 100%; 
    box-sizing: border-box;
    -moz-appearance: none;
    -webkit-appearance: none;
    appearance: none;
    background-color: #fff;
    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'),
      linear-gradient(to bottom, #ffffff 0%,#ffffff 100%);
    background-repeat: no-repeat, repeat;
    background-position: right .7em top 50%, 0 0;
    background-size: .65em auto, 100%;
    border: 0px!important;
    border-bottom: solid 1px #3cbc64!important; 
    color: #3cbc64!important;
    border-radius: 0.2rem;

  }

  .input-modal-customer {
     border: 0px!important;
    border-bottom: solid 1px #3cbc64!important; 
    color: #3cbc64!important;
    border-radius: 0.2rem;
    padding: 0px!important;
    width: 95%;
  }

  .span-selected-customer-rut {
    font-size: 14px;
    color: #3cbc64;
    font-style: italic;
  }

  .span-selected-customer-label {
    font-size: 10px;
    color: #3cbc64;
    font-style: italic;
  }

  .span-selected-customer-name {
    font-size: 12px;
    color: #1d3344;
    font-style: italic;
  }

  .span-selected-customer-value {
    font-size: 10px;
    color: #1d3344;
    font-style: italic;
  }

  .td-label-ticket-detail{
    font-size: 9px;
  }

  .td-label-ticket-detail{
    font-size: 9px;
  }

  .td-label-ticket-detail-danger{
    font-size: 12;
    color:red;
  }

  .finish-ticket {
    text-align: center;
  }
  .finish-ticket img{
    margin: 5px auto!important;
    width: 45px;
    height: 15px;  
    cursor: pointer;
    border-radius: 200px 200px 200px 200px;
    -moz-border-radius: 200px 200px 200px 200px;
    -webkit-border-radius: 200px 200px 200px 200px;
    border: 0px solid #000000;

    -webkit-box-shadow: 3px 3px 5px 0px rgba(217,236,255,1);
    -moz-box-shadow: 3px 3px 5px 0px rgba(217,236,255,1);
    box-shadow: 3px 3px 5px 0px rgba(217,236,255,1);
  }
  .finish-ticket img:hover{
    transition:all .5s ease-in-out;
    -webkit-transform:scale(1.3);
    -webkit-box-shadow: 5px 5px 5px 0px rgba(217,236,255,1);
    -moz-box-shadow: 5px 5px 5px 0px rgba(217,236,255,1);
    box-shadow: 5px 5px 5px 0px rgba(217,236,255,1);
  }

  .table-add-customer {
    width: 100%;
  }

  .table-add-customer tbody .td-key {
    color: #1d3344;
    font-size: 10px;
  }

  .select-modal-customer { 
    color: #1d3344!important;
    font-size: 10px;
  }
  
    
  .table-add-customer tbody .td-value {
    font-size: 10px;
    color: #3cbc64;
  }

  .h2-add-customer {
    font-size: 14px!important;
  }

  .table-add-customer tbody .td-value .errors {
    color: red!important;
    font-size: 10px!important;
  }

  .ticket-errors {
    color: red!important;
    font-size: 10px!important;
  }

</style>
@endsection
