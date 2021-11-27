<template >
    <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
      




      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>
         <router-link to="/inventories">               
            <p class="">&nbsp;inventarios&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">detalle</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Detalle de inventario cargara al maestro</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <router-link to="/inventories">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link> 
            </div>
          </div> 
      </div>
      
      <hr>
      <div class="row">
          <div class="col-md-12 card card-inventories wrapper-without-everflow">     
            <div class="card card-items-added">
              <div class="card-body">      
                <table class="table table-inventories">
                  <thead>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                  </thead> 
                  <tbody>
                    <tr v-for="(item, key_item) of product_list" :key="key_item" class="row-item" :class="colorToFlagRow(item.flag)">
                      <td style="width: 20%!important; text-align: left!important">{{item.barcode}}</td>
                      <td style="width: 50%!important ; text-align: left!important">{{item.name}}</td>
                      <td style="width: 15%!important; text-align: right!important">{{item.qty}} </td>
                    </tr>
                  </tbody> 
                </table>              
              </div>
            </div>
        </div>
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
        barcode: '',
        last_product: '',
        product_list: [],
                 
      }
    },       
    mounted () {     
      this.getInventories()
    },
    methods: {  
        colorToFlagRow(flag) {
          switch (flag) {
            case 'not_exist'      : return 'item-row-not-exist'; break;
            case 'not_allocation' : return 'item-row-not-allocation'; break;
            case 'ok'             : return 'item-row-ok'; break;
          }
        },
        getInventories () {
          var self = this
            this.$http.get('/get-inventory', {params: {id: self.id}})
              .then(response => { 
                self.loader = false 
                this.inventory    = response.body.inventory
                this.product_list = response.body.details                          
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
@endsection
