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
        <p class="text-muted mb-0 hover-cursor">crear</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Nuevo Producto</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para Guardar el nuevo registro." @click="postStore">
              <router-link to="/mono-company-products">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link> 
            </div>
          </div> 
      </div>
         <hr>              
      <div class="card">          
        <div class="card-body">            
          <form class="form-basic">       
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label>Código de barras</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Ingrese el código de barras para el producto"></span>
                      <input type="text" class="form-control form-control-sm" v-model="product.barcode" name="barcode">  
                      <span class="errors" v-if="errors && errors.barcode">{{ errors.barcode[0] }}</span>               
                    </div>
                    <div class="form-group col-md-6">
                      <label>Marca</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Seleccione la marca del producto"></span>
                      <select class="select-css" v-model="product.brand_id" name="brand_id">
                        <option v-for="(brand, key_brands) of brands" :key="key_brands" :value="brand.id">{{brand.code}}</option>
                      </select>
                      <span class="errors" v-if="errors && errors.brand_id">{{ errors.brand_id[0] }}</span>               
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label>Nombre</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Ingrese un nombre para el producto"></span>
                      <input type="text" class="form-control form-control-sm" v-model="product.name" name="name" >
                      <span class="errors" v-if="errors && errors.name">{{ errors.name[0] }}</span>               
                    </div>
                    <div class="form-group col-md-6">
                      <label>Categoría</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Seleccione una categoría para el producto"></span>
                      <select class="select-css" v-model="product.category_id" @change="getSubcategories()">
                        <option v-for="(category, key_categories) of categories" :key="key_categories" :value="category.id">{{category.code}}</option>
                      </select>
                      <span class="errors" v-if="errors && errors.category_id">{{ errors.category_id[0] }}</span>               
                    </div>
                  </div>
                  <div class="row">                
                    <div class="form-group col-md-6">
                      <label>Subcategoría</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Seleccione una subcategoría para el producto"></span>
                      <select class="select-css" v-model="product.subcategory_id" request>
                        <option v-for="(subcategory, key_subcategories) of subcategories" :key="key_subcategories" :value="subcategory.id">{{subcategory.code}}</option>
                      </select>
                      <span class="errors" v-if="errors && errors.subcategory_id">{{ errors.subcategory_id[0] }}</span>               
                    </div>
                  </div>
                  <div class="">
                    <label>Descripción</label>
                    <span class="mdi mdi-alert-circle circle-info" title="Ingrese una descripción para el producto"></span>
                    <textarea  class="form-control form-control-sm" v-model="product.description" ></textarea>
                    <span class="errors" v-if="errors && errors.description">{{ errors.description[0] }}</span>               
                  </div>
          </form>
        </div>
      </div>          
    </div>   
</template>
<script>
  import Util from '../../mixins/Util.js'
  export default {
    mixins: [Util],
    data () {
      return {
        errors    : {},
        product : {
            _token         : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            barcode        : "",
            name           : "",
            description    : "",
            base_price     : "",
            post_event     : "store",
            brand_id       : '',
            category_id    : '',
            subcategory_id : '',
        },
        categories : [],
        subcategories: [],
        brands: []

      }
    },  
    mounted () {
      this.initData()
    },  
    methods: { 
       async initData () {
          await this.getCategories()
          await this.getBrands()
          this.loader = false
        },             
      postStore () {
        event.preventDefault()
        var self    = this  
        this.loader = true
        this.$http.post('/mono-company-products-store', this.product)
        .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            self.$router.push({ path: "/mono-company-products"})            
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
        var self    = this 
        this.loader = true 
        this.$http.get('/get-categories-subcategories', {params: {category_id: this.product.category_id}})
        .then(response => {
            self.loader = false
            self.subcategories = response.data
            self.product.subcategory_id = ''
          }, response => {  
             self.loader = false               
              self.mixinGenericError(response)
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
@endsection
