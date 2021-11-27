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
        <p class="text-muted mb-0 hover-cursor">editar</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Editar recepción</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para guardar la recepción." @click="finish()">

              <router-link to="/receptions">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link> 

            </div>
          </div> 
      </div>    
      <hr>     
      <div class="">
        <div class="col-md-12">
          <div class="row form-basic">
            <div class="col-md-4">
              <label>Proveedor</label>
              <span class="mdi mdi-alert-circle circle-info" title="Si el proveedor no esta en la lista registrelo en el Módulo de Proveedores."></span>
              <select class="select-css" v-model="reception.provider_id">
                <option v-for="(provider, key_providers) of providers" :key="key_providers" :value="provider.id">[ {{provider.rut}} ] {{provider.name}}</option>
              </select>
              <span class="errors" v-if="errors && errors.provider_id">{{ errors.provider_id[0] }}</span>               
            </div>
            <div class="col-md-4">
              <label>Identificador/Guía</label>
              <span class="mdi mdi-alert-circle circle-info" title="Ingrese el identificador el evento. Puede ser el número de docuento con el cual se recibieron los productos o algún otro número de referecia para el evento."></span>
              <input type="text" class="form-control form-control-sm" maxlength="9" @keypress="isNumber($event)" v-model="reception.guide">       
              <span class="errors" v-if="errors && errors.guide">{{errors.guide[0] }}</span>             
            </div>
            <div class="col-md-4">
              <label>Fecha del documento</label>
              <span class="mdi mdi-alert-circle circle-info" title="Seleccione la fecha de emisión del documento que se utilizo como referencia o, en su defecto, ingrese la fecha actual."></span>
              <input type="date" class="form-control form-control-sm"  v-model="reception.guide_date"  onkeydown="return false">       
              <span class="errors" v-if="errors && errors.guide_date">{{errors.guide_date[0] }}</span>            
            </div> 
          </div>
        </div>
        <hr>
        <div class="row container">            
          <div class="col-md-6">
            <div class="form-basic">
              <label>Código de barras</label>
              <span class="mdi mdi-alert-circle circle-info" title="Ingrese el código de barras del producto"></span>
              <span class="aviso-presione-enter">*Para su ingreso manual, ingrese el código y luego presion el botón ENTER.</span>
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
            <table class="table table-receptions">
              <thead>
                <th>Código</th>
                <th>Descripción</th>
                <th>Costo proveedor</th>
                <th>Cantidad</th>
                <th>Acciones</th>
              </thead> 
              <tbody>
                <tr v-for="(item, key_item) of product_list" :key="key_item" class="row-item" :class="colorToFlagRow(item.flag)">
                  <td style="width: 20%!important; text-align: left!important">{{item.barcode}}</td>
                  <td style="width: 30%!important ; text-align: left!important">{{item.name}}</td>
                  <td style="width: 20%!important ; text-align: left!important"><input  v-if="item.flag != 'not_exist'" type="text" class="input-change-price-table" v-model="item.cost_provider" maxlength="8" @keypress="isNumber($event)">  <span v-else></span></td>
                  <td style="width: 15%!important; text-align: right!important"> <input type="number" class="input-change-price-table"  v-model="item.qty" @change="changeQtyInput()" maxlength="7" @keypress="isNumber($event)"> </td>
                  <td style="width: 15%!important ; text-align: center!important">
                    <img src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para eliminar el item"  @click="deleteItem(key_item)">
                    <img v-if="item.flag=='not_allocation'" src="/intranet/img/icons/enlace.png" class="hover-cursor icons-action-row-form" title="Presione para añadire el producto al mastro de la compañía"  @click="addMaster(item, key_item)">
                    <img v-if="item.flag=='not_exist'" src="/intranet/img/icons/stock-prod.png" class="hover-cursor icons-action-row-form" title="Presione para añadire el producto al mastro de la compañía"  @click="createProduct(item, key_item)">

                  </td>
                </tr>
              </tbody> 
            </table>              
          </div>
        </div>
      </div>


      <modal name="modal-create-product" :width="750" :height="600" :adaptive="true" :resizable="true" :draggable="true">
        <div class="modal-body ">
          <div class="modal-content">         
            
            <div class="row div-title-module">
              <div class="col-md-10">
                <h2 class="h2-add-customer">Registrar  nuevo producto</h2>          
              </div>
              <div class="col-md-2">
                  <div class="btn-main-view-action">
                    <span><img src="/intranet/img/icons/comprobar.png" alt="" class="icon-action-module float-right" @click="storeNewProduct()"></span>  
                  </div>
              </div> 
            </div>
            
            <div class="scroll-to-modal-body-customer">
              <hr>
              <form class="form-modal-customer-ticket">  
                <table class="table-add-customer">
                  <tbody>
                    <tr>
                      <td class="td-key">Código de barras</td>
                      <td class="td-value">
                        <input type="text" class="form-control form-control-sm" v-model="new_product.barcode" name="barcode" readonly>  
                        <span class="errors" v-if="errors && errors.barcode">{{ errors.barcode[0] }}</span>   
                      </td>  
                    </tr> 
                    <tr>
                      <td class="td-key">Nombre</td>
                      <td class="td-value">
                        <input type="text" class="form-control form-control-sm" v-model="new_product.name" name="name" >
                        <span class="errors" v-if="errors && errors.name">{{ errors.name[0] }}</span>       
                      </td>  
                    </tr> 
                    <tr>
                      <td class="td-key">Marca</td>
                      <td class="td-value">
                        <select class="select-css" v-model="new_product.brand_id" name="brand_id">
                          <option v-for="(brand, key_brands) of brands" :key="key_brands" :value="brand.id">{{brand.code}}</option>
                        </select>
                        <span class="errors" v-if="errors && errors.brand_id">{{ errors.brand_id[0] }}</span>        
                      </td>  
                    </tr> 

                    <tr>
                      <td class="td-key">Categoría</td>
                      <td class="td-value">
                        <select class="select-css" v-model="new_product.category_id" @change="getSubcategories()">
                          <option v-for="(category, key_categories) of categories" :key="key_categories" :value="category.id">{{category.code}}</option>
                        </select>
                        <span class="errors" v-if="errors && errors.category_id">{{ errors.category_id[0] }}</span>         
                      </td>  
                    </tr> 

                    <tr>
                      <td class="td-key">Subcategoría</td>
                      <td class="td-value">
                        <select class="select-css" v-model="new_product.subcategory_id" request>
                          <option v-for="(subcategory, key_subcategories) of subcategories" :key="key_subcategories" :value="subcategory.id">{{subcategory.code}}</option>
                        </select>
                        <span class="errors" v-if="errors && errors.subcategory_id">{{ errors.subcategory_id[0] }}</span>      
                      </td>  
                    </tr> 

                    <tr>
                      <td class="td-key">Descripción</td>
                      <td class="td-value">
                        <textarea  class="form-control form-control-sm" v-model="new_product.description" ></textarea>
                        <span class="errors" v-if="errors && errors.description">{{ errors.description[0] }}</span> 
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
  export default {
    mixins: [Util],
    data () {
      return {
        errors      : {},
        id          : this.$route.params.id,
        reception   : {
          provider_id  : '',
          guide        : '',
          guide_date   : '',
        },
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
        providers    : [],
        new_product : {
            _token         : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            barcode        : "",
            name           : "",
            description    : "",
            post_event     : "store",
            brand_id       : '',
            category_id    : '',
            subcategory_id : '',
            key_item       : ''
        },
        categories : [],
        subcategories: [],
        brands: [],
        list_no_code: ''
                 
      }
    },       
    mounted () {     
      this.initData()
    },
    methods: {  
      async initData () {
        this.getReception()
        this.getProviders()   
        this.getCategories()
        this.getBrands()
        this.loader = false
      }, 
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
              this.$http.post('/reception-add-item-to-master', data)
              .then(response => {
                self.$toasted.global.APP_GENERAL_SUCCESS()
                self.product_list[key_item].flag = 'ok'
                localStorage.setItem('current_reception', JSON.stringify(self.product_list));
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
          var get_reception = false
          if (localStorage.getItem('current_edit_reception')) {
            var local_reception = JSON.parse(localStorage.getItem('current_edit_reception'))
            if (parseInt(local_reception.id) != parseInt(this.id) ) {
              this.loader = false
               this.$swal({
                  title             : 'Continuación de edición de recepción [ ' + local_reception.id + ' ]',
                  text              : 'Existen datos relaciónados a la edición de otra recepción y esta no se finalizó. Presiona "Sí" para continuar con la edición de dicha recepción o presione "No" para eliminar los datos mensionados y comenzar con la edición actual',
                  type              : 'warning',
                  showCancelButton  : true,
                  confirmButtonText : 'Sí',
                  cancelButtonText  : 'No',
                  showCloseButton   : true
                }).then((result) => {
                  if(result.value) {
                    console.log('redirijo despues del si ')  
                      this.$router.replace({path: '/receptions/edit/'+local_reception.id}) 
                      this.id           = this.$route.params.id
                      this.reception    =  JSON.parse(localStorage.getItem('current_edit_reception'))                     
                      this.product_list =  JSON.parse(localStorage.getItem('current_edit_detail_reception')) 
                      this.loader = false
                  } else {
                    console.log('cargo desde srrvoidor')
                      var self = this
                      this.$http.get('/get-reception', {params: {id: self.id}})
                        .then(response => {  
                          self.reception    = response.body.reception
                          self.product_list = response.body.details
                          self.loader = false
                          localStorage.setItem('current_edit_reception', JSON.stringify(self.reception)); 
                          localStorage.setItem('current_edit_detail_reception', JSON.stringify(self.product_list)); 
                        }, response => {  
                          self.loader = false               
                          self.$toasted.global.APP_GENERAL_ERROR()
                      })   
                  }
                })
            } else {
              console.log('aqui llego y cargo la wea desde el localstorage')
              this.loader = false
              this.reception    =  JSON.parse(localStorage.getItem('current_edit_reception'))    
              this.product_list =  JSON.parse(localStorage.getItem('current_edit_detail_reception'))  
            }            
          } else {
            console.log('en el else definitivo')
             var self = this
              this.$http.get('/get-reception', {params: {id: self.id}})
                .then(response => {    
                  self.reception    = response.body.reception
                  self.product_list = response.body.details    
                  self.loader = false             
                  localStorage.setItem('current_edit_reception', JSON.stringify(self.reception)); 
                  localStorage.setItem('current_edit_detail_reception', JSON.stringify(self.product_list)); 
                }, response => {                 
                    self.$toasted.global.APP_GENERAL_ERROR()
              })    
          }
      },    
      changeQtyInput () {
        localStorage.setItem('current_edit_detail_reception', JSON.stringify(this.product_list));
      },   
      async addItem  () {
        window.event.preventDefault()
        if (window.event.keyCode === 13) {
          if (this.barcode) {
            var self =  this
            var has  = false
            this.product_list.forEach(function (v, i) {
              if(v.barcode == self.barcode) {                                            
                self.product_list[i].qty = parseInt(self.product_list[i].qty) + 1
                self.last_product        =  self.product_list[i]
                has                      = true
                self.barcode             = ''
              }
            })
            if (!has) {            
                  await this.$http.get('/get-product-company-receptions', {params: {barcode: this.barcode}})
                  .then(response => { 
                      self.last_product     = response.body
                      self.last_product.qty = 1
                      self.product_list.push(self.last_product) 
                      self.barcode          = ''
                  }, response => {                 
                      self.$toasted.global.APP_GENERAL_ERROR()
                  })
                } 
              localStorage.setItem('current_edit_reception', JSON.stringify(self.reception)); 
              localStorage.setItem('current_edit_detail_reception', JSON.stringify(self.product_list)); 
            }
        }
      },
      deleteItem (key_item) {
        this.$swal({
          title             : '¿Esta seguro/a?',
          text              : 'Esta apunto de eliminar el registro contabilizado.',
          type              : 'warning',
          showCancelButton  : true,
          confirmButtonText : 'Sí',
          cancelButtonText  : 'No',
          showCloseButton   : true
        }).then((result) => {
          if(result.value) {                      
            this.product_list.splice(key_item, 1)     
            localStorage.setItem('current_edit_detail_reception', JSON.stringify(self.product_list));                    
          } 
        })                     
      },
      finish () {
        var self = this        
        if (this.product_list.length > 0) {        
          window.event.preventDefault()
          this.loader       = true
          this.list_no_code = ""
          this.product_list.forEach(function(val, ind){            
            if (val.id == 'null') {
              self.list_no_code += "[" + val.barcode + "] "
            }                     
          })
          
          if (this.list_no_code) {
            this.loader = false
            this.$swal.fire({
              icon: 'error',
              title: 'Atención',
              text: "No se puede ingresar la recepción por los siguientes códigos sin identificar: " + this.list_no_code,
              
            })
            return
          }        
          var data = {
            _token      : this.product._token,
            id          : this.id,
            provider_id : this.reception.provider_id,
            guide       : this.reception.guide,
            guide_date  : this.reception.guide_date,
            list        : this.product_list
          }
          this.$http.post('/reception-update', data)
          .then(response => {
            self.loader = false
              localStorage.removeItem("current_edit_reception")
              localStorage.removeItem("current_edit_detail_reception")
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.$router.push({ path: "/receptions"})
            }, response => {   
              self.loader = false              
              if (response.status === 422) {
                this.$toasted.global.APP_GENERAL_ERROR_FORM()
                this.errors = response.body.errors
              }else{
                  this.$toasted.global.APP_GENERAL_ERROR()
              }

            }) 
        } else {
          this.$swal.fire({
            icon: 'error',
            title: 'Atención',
            text: "No se ha registrado ningún producto",
            
          })
        }
      },
      getBrands () {
        var self = this
        this.$http.get('/get-code-list-for-type', {params : {type:"PRODUCT_BRAND"}})
        .then(response => {    
            self.brands = response.body                       
        }, response => {                 
            self.$toasted.global.APP_GENERAL_ERROR()
        })
      },  
      getCategories () {
        var self = this  
        this.$http.get('/get-code-list-for-type', {params : {type:"PRODUCT_CATEGORY"}})
        .then(response => {
              self.categories = response.data
          }, response => {                 
              self.mixinGenericError(response)
          })
      },
      getSubcategories () {
        var self = this 
        this.loader = true 
        this.$http.get('/get-categories-subcategories', {params: {category_id: this.new_product.category_id}})
        .then(response => {
            self.loader = false
            self.subcategories = response.data
            self.product.subcategory_id = ''
          }, response => {  
             self.loader = false               
              self.mixinGenericError(response)
          })
      },
      createProduct(value, key_item) {
        this.new_product.barcode = value.barcode
        this.new_product.key_item = key_item
        this.$modal.show("modal-create-product")
      },
      hideCreateProduct() {
        this.$modal.hide("modal-create-product")
      },
      storeNewProduct () {
        window.event.preventDefault()
        var self    = this  
        this.loader = true
        this.$http.post('/mono-company-products-store', this.new_product)
        .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            self.loader = false
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.product_list[self.new_product.key_item].flag        = 'ok'
            self.product_list[self.new_product.key_item].name        = response.body.name
            self.product_list[self.new_product.key_item].id          = response.body.id
            self.product_list[self.new_product.key_item].description = response.body.description
            self.product_list[self.new_product.key_item].base_price  = null
            localStorage.setItem('current_edit_reception', JSON.stringify(self.reception)); 
            localStorage.setItem('current_edit_detail_reception', JSON.stringify(self.product_list)); 
           
            self.hideCreateProduct()            
            self.new_product.barcode        = ""
            self.new_product.name           = ""
            self.new_product.description    = ""
            self.new_product.brand_id       = ""
            self.new_product.category_id    = ""
            self.new_product.subcategory_id = ""
            self.new_product.key_item       = ""       
              
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
    },
   
  }
</script>

<style>

</style>
@endsection
