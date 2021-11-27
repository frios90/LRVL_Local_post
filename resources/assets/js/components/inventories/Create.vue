<template >
    <div class="content-wrapper ">
       <div v-if="loader" class="loader"></div>
      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/inventories">               
            <p class="">&nbsp;inventarios&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">nuevo</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Nuevo inventario de productos</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para guardar la inventario." @click="finish()">

              <router-link to="/inventories">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link> 

            </div>
          </div> 
      </div>   
      <hr>      
      <div class="row">     

        <div class="col-md-12 card card-inventories wrapper-without-everflow">
            <div class="row">            
              <div class="form-group col-md-6">
                <div class="form-basic">
                  <label>Código de barras</label> <span class="aviso-presione-enter">*Para su ingreso manual, ingrese el código y luego presion el botón ENTER.</span>
                  <span class="mdi mdi-alert-circle circle-info" title="Ingrese el código de barras del producto"></span>
                  <input autofocus type="text" class="form-control form-control-sm" v-model="barcode" name="barcode" @keyup="addItem()" maxlength="30">  
                  <span class="errors" v-if="errors && errors.barcode">{{ errors.barcode[0] }}</span>              
                </div>  
              </div> 
              <div>               
                <div v-if="last_product">
                  <h5 class="title-detail-add">Detalle último producto ingresado</h5>
                  <div class="barcode-detail-add">                
                    {{last_product.barcode}}
                  </div> 
                  <div class="description-detail-add">                  
                    {{last_product.name}}  
                  </div> 
                  <div class="qty-detail-add">                
                    {{last_product.qty}}  
                  </div>   
                </div>          
              </div>              
            </div>
            <div class="card card-items-added">
              <div class="card-body">      
                <table class="table table-inventories">
                  <thead>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                  </thead> 
                  <tbody>
                    <tr v-for="(item, key_item) of product_list" :key="key_item" class="row-item" :class="colorToFlagRow(item.flag)">
                      <td style="width: 20%!important; text-align: left!important">{{item.barcode}}</td>
                      <td style="width: 50%!important ; text-align: left!important">{{item.name}}</td>
                      <td style="width: 15%!important; text-align: right!important"> <input type="number" class="input-change-price-table"  v-model="item.qty" @change="changeQtyInput()" maxlength="7" @keypress="isNumber($event)"> </td>
                      <td style="width: 15%!important ; text-align: center!important">
                        <img src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para eliminar el item"  @click="deleteItem(key_item)">
                        <img v-if="item.flag=='not_allocation'" src="/intranet/img/icons/enlace.png" class="hover-cursor icons-action-row-form" title="Presione para añadire el producto al mastro de la compañía"  @click="addMaster(item, key_item)">
                      </td>
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
        product     : {
            _token      : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            barcode     : "",
            name        : "",
            description : "",
            base_price  : "",
            qty         : ""  
        },
        barcode      : '',
        last_product : '',
        product_list : [],                       
      }
    },       
    mounted () { 
      this.loader = false    
      if (localStorage.getItem('current_inventory')) {
         this.$swal({
            title              : 'Hay un inventario pendiente',
            text               : 'Si acepta se cargarán los datos para continuar con el evento, de lo contrario se descartarán los datos',
            type               : 'warning',
            showCancelButton   : true,
            confirmButtonText  : 'Sí',
            cancelButtonText   : 'No',
            showCloseButton    : true
          }).then((result) => {
            if(result.value) {                      
              this.product_list =  JSON.parse(localStorage.getItem('current_inventory'))                           
            } else {
             localStorage.removeItem('current_inventory');
            }
          })
      }
    },
    methods: { 
      colorToFlagRow(flag) {
        switch (flag) {
          case 'not_exist'      : return 'item-row-not-exist'; break;
          case 'not_allocation' : return 'item-row-not-allocation'; break;
          case 'ok'             : return 'item-row-ok'; break;
        }
      },
      addMaster(item, key_item) {
          this.$swal({
            title              : '¿Esta seguro/a?',
            text               : 'Si acepta, el producto pasara a ser parte del maestro de la empresa.',
            type               : 'warning',
            showCancelButton   : true,
            confirmButtonText  : 'Sí',
            cancelButtonText   : 'No',
            showCloseButton    : true
          }).then((result) => {
            if(result.value) {                      
              event.preventDefault()
              var self = this  
              var data = {
                _token      : this.product._token,
                product_id  : item.id,                
              }
              this.$http.post('/inventory-add-item-to-master', data)
              .then(response => {
                self.$toasted.global.APP_GENERAL_SUCCESS()
                self.product_list[key_item].flag = 'ok'
                localStorage.setItem('current_inventory', JSON.stringify(self.product_list));
              }, response => {                 
                  if (response.status === 422) {
                      this.$toasted.global.APP_GENERAL_ERROR_FORM()
                      this.errors = response.body.errors
                  }else{
                      this.$toasted.global.APP_GENERAL_ERROR()
                  }
              })
                          
            } 
          })        
      },
      changeQtyInput () {
        localStorage.setItem('current_inventory', JSON.stringify(this.product_list));
      },    
      async addItem  () {
        if (this.barcode) {
          event.preventDefault()
          if (event.keyCode === 13) {
            var self =  this
            var has  = false
            this.product_list.forEach(function (v, i) {
              if(v.barcode == self.barcode) {                                            
                self.product_list[i].qty = parseInt(self.product_list[i].qty) + 1
                self.last_product =  self.product_list[i]
                has = true
                self.barcode = ''
              }
            })
            if (!has) {            
                await this.$http.get('/get-product-company-inventories', {params: {barcode: this.barcode}})
                .then(response => { 
                    self.last_product = response.body
                    self.last_product.qty = 1
                    self.product_list.push(self.last_product) 
                    self.barcode = ''

                }, response => {                 
                    self.$toasted.global.APP_GENERAL_ERROR()
                })
              } 
              localStorage.setItem('current_inventory', JSON.stringify(this.product_list))
            }
        }
        
      },
      deleteItem (key_item) {
        this.$swal({
            title              : '¿Esta seguro/a?',
            text               : 'Si presiona sí, el registro sera elimindo del listado',
            type               : 'warning',
            showCancelButton   : true,
            confirmButtonText  : 'Sí',
            cancelButtonText   : 'No',
            showCloseButton    : true
          }).then((result) => {
            if(result.value) {                      
                this.product_list.splice(key_item, 1)     
                localStorage.setItem('current_inventory', JSON.stringify(this.product_list));                    
            } 
          })                     
      },
      finish () {
        if (this.product_list.length > 0) {
          this.loader = true
          event.preventDefault()
          var self = this  
          var data = {
            _token      : this.product._token,
            provider_id : this.provider_id,
            guide       : this.guide,
            guide_date  : this.guide_date,
            list        : this.product_list
          }
          this.$http.post('/inventory-finish', data)
          .then(response => {
            localStorage.removeItem("current_inventory")
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            self.$router.push({ path: "/inventories"})
          }, response => {    
            self.loader = false             
              if (response.status === 422) {
                  this.$toasted.global.APP_GENERAL_ERROR_FORM()
                  this.errors = response.body.errors
              }else{
                  this.$toasted.global.APP_GENERAL_ERROR()
              }
          })
        }
        
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

.wrapper-without-everflow{
    overflow-y: hidden!important; 
    margin-right: 20px;
}
  .card-inventories {
    padding: 50px 25px;
  }

  .row-item td {
    height: 10px!important;
    padding: 2px 10px!important;
    color: #444;
    font-size: 11px;
  }

  .item-row-not-exist {
    background-color: #f7e6e3;
  }

  .item-row-not-allocation {
    background-color: #dbeaf9;
  }

   .item-row-ok {
    background-color: #e3f7e5;
  }

  .card-items-added {
    -webkit-box-shadow: -200px -200px 0px -200px rgba(0,0,0,0.75);
-moz-box-shadow: -200px -200px 0px -200px rgba(0,0,0,0.75);
box-shadow: -200px -200px 0px -200px rgba(0,0,0,0.75);



  }

  .table-inventories thead{
    background-color: red;
    background: rgb(29,51,68);
	background: linear-gradient(90deg, rgba(29,51,68,1) 0%, rgba(49,79,86,1) 47%, rgba(61,95,96,1) 97%, rgba(61,95,96,1) 98%, rgba(87,131,119,1) 99%, rgba(170,246,193,1) 100%); 
  color: #fff;
  letter-spacing: 2px;
  }

  .table-inventories thead th {
   
    border-top: 0!important;
    border-bottom-width: 1px!important;
    font-weight: 400!important;
    font-size: 11px!important;
    padding: 10px !important;
  }

  .inventory-number {
    background: transparent;
    color: #444!important;
    text-align: center;
    border: none!important;
    font-size: 12px;
    font-weight: 600;
}

.title-detail-add {
  color:  #3cbc64!important;
  font-size: 10px!important;

}

.barcode-detail-add {
  color:  #1d3344!important;
  font-size: 14px!important;
  
}

.description-detail-add {
  color:  #3cbc64!important;
  font-size: 12px!important;
  
}

.qty-detail-add {
  color:  #1d3344!important;
  font-size: 16px!important;
  
}

.aviso-presione-enter {
  font-size: 9px;
}
</style>
@endsection
