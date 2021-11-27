<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>               
      <div v-if="sale" class="row">  
        
        <div class="col-md-12">  
          <div class="d-flex div-navegate-page">    
            <router-link to="/dashboard">               
                <p class="">inicio&nbsp;/</p>             
            </router-link>
            <router-link to="/sales">               
                <p class="text-muted mb-0 hover-cursor">&nbsp;ventas&nbsp;/&nbsp;</p>             
            </router-link>
            <p class="text-muted mb-0 hover-cursor">detalle</p>             
          </div>   
          <div class="row div-title-module">
            <div class="col-md-10">
              <h2>Detalle de la {{sale.code.label}} : {{sale.number}} <span>[ {{sale.step.label}} ]</span> </h2>         
            </div>   
            <div class="col-md-2">
                <div class="btn-main-view-action">                  
                  <router-link to="/sales">
                    <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  :title="msg_return_menu">
                  </router-link> 
                </div>
              </div>      
          </div> 
          <hr>          
          <div class="row">
           
            <div class="col-md-6">
              <div>
                <h3 class="h3-see-sale">Reponsable</h3>
                <hr>
                <div><span class="span-selected-customer-rut">{{sale.user.rut}}</span> <span> | </span> <span class="span-selected-customer-name">{{sale.user.name}}</span></div>               
                <div><span class="span-selected-customer-label">Emitida: </span><span class="span-selected-customer-value">{{sale.created_at}}</span></div>

              </div>
            </div>
           

             <div class="col-md-6">
              <table style="width: 100%">
                  <tbody class="table-bordered">
                    <tr>
                      <td class="td-label-ticket label-type-ticket-see">
                        {{sale.code.label}}
                      </td>
                      <td class="td-value-ticket">{{sale.number}}</td>
                    </tr>
                    <tr>
                      <td class="td-label-base">Total Base</td>
                      <td class="td-value-iva">{{sale.base | currency}}</td>
                    </tr>
                    <tr>
                      <td class="td-label-iva">Total Iva</td>
                      <td class="td-value-base">{{sale.iva | currency}}</td>
                    </tr>
                    <tr>
                      <td class="td-label-total">Total a Pago</td>
                      <td class="td-value-total">{{sale.total | currency}}</td>
                    </tr>
                  </tbody>
                </table>
            </div>
           
          </div>
          <hr>
          <div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-receptions table-bordered">
                  <thead>
                    <th style="width: 10%!important; text-align: center!important">Código</th>
                    <th style="width: 30%!important; text-align: left!important;">Descripción</th>
                    <th style="width: 15%!important; text-align: center!important">Cantidad</th>
                    <th style="width: 15%!important; text-align: center!important">$ Unidad</th>
                    <th style="width: 15%!important; text-align: center!important">$ Total</th>
                  </thead> 
                  <tbody>
                    <tr v-for="(item, key_item) of sale.detail" :key="key_item" class="row-item-sale">                      
                      <td style="width: 10%!important; text-align: left!important; font-size: 10px">{{item.barcode}}</td>
                      <td style="width: 30%!important ; text-align: left!important; font-size: 11px">{{item.name}}</td>
                      <td style="width: 15%!important; text-align: center!important"> {{item.qty}} </td>
                      <td style="width: 15%!important ; text-align: center!important">{{item.base | currency}}</td>
                      <td style="width: 15%!important ; text-align: center!important">{{item.total | currency}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>          
        </div>
      </div>     
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
        id : this.$route.params.id,
        sale: '',
        token : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        post_document : {
          id : this.$route.params.id,
          _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          type       : '',
          identifier : ''
        }
      }
    },
    created () {
      this.getSale()
    },
    methods: {      
      getSale (search) {           
        var self = this
        this.$http.get('/get-sale', {params: {id: this.post_document.id}})
        .then(response => {    
            self.sale = response.body    
            self.loader = false            
        }, response => {       
            self.loader = false          
            self.$toasted.global.APP_GENERAL_ERROR()
        })            
      },
      aceptQuotation () {
        this.$swal({
          title             : 'Aceptar cotización',
          text              : 'Al aceptar una cotización, se generá un nuevo tiquet de venta, reservando los productos al cliente.',
          type              : 'success',
          showCancelButton  : true,
          confirmButtonText : 'Sí',
          cancelButtonText  : 'No',
          showCloseButton   : true
        }).then((result) => {
          if(result.value) {
            event.preventDefault() 
            this.loader=true     
              var self = this  
              var send = {
                id: this.id,
                _token:this.token
                
              }
              this.$http.post('/sale-acept-quotation', send)
              .then(response => {
                  self.$toasted.global.APP_GENERAL_SUCCESS()
                  self.errors = {}
                  self.loader = false
                  this.getSale()
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
      completeSale () {
        event.preventDefault()      
        var self = this  
        this.loader = true
        this.$http.post('/sale-complete-event', this.post_document)
        .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            this.getSale()
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
  .label-type-ticket-see {
    font-size: 25px!important;
  }
  .input-doc-asociado-sale {
   width: 100%;
  }

  .h3-see-sale {
    color: #2d363e !important;
    font-size: 16px !important;
    font-weight: 300;
    margin-bottom: -5px!important;
    letter-spacing: 0.1em;
  }
  .select-qts {
    width: 100%!important;
  }

  .btn-block-tseal {
    margin-top: 5px;
    width: 100%;
  }
</style>
@endsection
