<template >
    <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>
         <router-link to="/receptions">               
            <p class="">&nbsp;recepciones&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">detalle</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Detalle de recepción cargada al maestro</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <router-link to="/receptions">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link> 
            </div>
          </div> 
      </div>

      <hr>
      <div class="">
        <div class="">
          <table class="table-bordered" style="width: 100%">
            <tbody>
              <tr>
                <td class="td-label">Proveedor</td>
                <td class="td-value">{{reception.provider}}</td>
              </tr>
              <tr>
                <td class="td-label">Identificador/Guía</td>
                <td class="td-value">{{reception.guide}}</td>
              </tr>
              <tr>
                <td class="td-label">Fecha del Documento</td>
                <td class="td-value">{{reception.guide_date}}</td>
              </tr>
              <tr>
                <td class="td-label">Fecha del Recepción</td>
                <td class="td-value">{{reception.updated_at}}</td>
              </tr>
            </tbody>
          </table>          
        </div>
        <table class="table-bordered table-receptions" style="width: 100%">
          <thead>
            <th>Código</th>
            <th style="width: 50%!important ; text-align: center!important">Descripción</th>
            <th style="width: 15%!important; text-align: center!important">Costo proveedor</th>
            <th style="width: 15%!important; text-align: center!important">Cantidad</th>
          </thead> 
          <tbody>
            <tr v-for="(item, key_item) of product_list" :key="key_item" class="row-item" :class="colorToFlagRow(item.flag)">
              <td style="width: 20%!important; text-align: left!important">{{item.barcode}}</td>
              <td style="width: 50%!important ; text-align: center!important">{{item.name}}</td>
              <td style="width: 50%!important ; text-align: center!important">{{item.cost_provider | currency}}</td>
              <td style="width: 15%!important; text-align: center!important">{{item.qty}} </td>
            </tr>
          </tbody> 
        </table>              
       
      </div>          
    </div>   
</template>
<script>
  import Util from '../mixins/Util.js'
  export default {
    mixins: [Util],
    data () {
      return {
        errors      : {},
        id          : this.$route.params.id,
        inventory   : '',
        product : {
            _token      : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            barcode     : "",
            name        : "",
            description : "",
            base_price  : "",
            qty         : ""  
        },
        reception   : {
          provider_id  : '',
          guide        : '',
          guide_date   : '',
        },
        barcode: '',
        last_product: '',
        product_list: [],
        providers    : []
                 
      }
    },       
    mounted () {     
      this.getReception()
      this.getProviders()
    },
    methods: {  
        colorToFlagRow(flag) {
          switch (flag) {
            case 'not_exist'      : return 'item-row-not-exist'; break;
            case 'not_allocation' : return 'item-row-not-allocation'; break;
            case 'ok'             : return 'item-row-ok'; break;
          }
        },
        getProviders () {
          var self = this
          this.$http.get('/get-providers-selector', {params: {barcode: this.barcode}})
            .then(response => { 
              self.providers = response.body      
            }, response => {                 
              self.$toasted.global.APP_GENERAL_ERROR()
            })
        },
        getReception () {
          this.loader = true
          var self = this
            this.$http.get('/get-reception-to-see', {params: {id: self.id}})
              .then(response => { 
                self.loader = false 
                self.reception    = response.body.reception
                self.product_list = response.body.details                          
              }, response => {             
                self.loader = false     
                self.$toasted.global.APP_GENERAL_ERROR()
            })    
      } 
    },
    filters: {
      upper: function (value) {
          return value.toUpperCase();
      }
    }
  }
</script>
<style>
  .td-label {
    width: 250px;
    padding: 5px 10px;
    font-size: 12px;
    color: #fff;
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgba(29, 51, 68, 1) 0%, rgba(170, 246, 193, 1) 100%);
  }
  .td-value {
    padding: 5px 5px 5px 25px;
    font-size: 12px;
    color: rgba(29, 51, 68, 1);
    text-align: left!important;
  }
</style>
@endsection
