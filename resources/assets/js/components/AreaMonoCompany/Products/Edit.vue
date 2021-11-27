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
        <p class="text-muted mb-0 hover-cursor">editar</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Editar Producto [ {{ product.name }} ]</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para Guardar el nuevo registro." @click="postUpdate">
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
        errors  : {},         
        product : {
          id        : this.$route.params.id,
          _token         : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          barcode        : "",
          name           : "",
          description    : "",
          post_event     : "update",
          brand_id       : '',
          category_id    : '',
          subcategory_id : '',
        },
        categories    : [],
        subcategories : [],
        brands        : []
      }
    },       
    created() {       
      this.initData()
    },  
    
    
    methods: {  
       async initData () {
          await this.getData()
          await this.getCategories()
          await this.getBrands()
          this.loader = false
        },   
      getData () {
        var self = this
        this.$http.get('/get-mono-company-products', {params: {id: self.product.id}})
        .then(response => {      
          self.product.barcode        = response.body.company_product.product.barcode
          self.product.name           = response.body.company_product.product.name
          self.product.description    = response.body.company_product.product.description
          self.product.brand_id       = response.body.company_product.product.brand_id
          self.product.subcategory_id = response.body.company_product.product.category.id
          self.product.category_id    = response.body.company_product.product.category.parent.id
          self.subcategories          = response.body.subcategories                  
        }, response => {                 
          self.$toasted.global.APP_GENERAL_ERROR()
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
            self.subcategories = response.data
            self.product.subcategory_id = ''
            self.loader = false
          }, response => {    
              self.loader = false             
              self.mixinGenericError(response)
          })
      },            
      postUpdate () {
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
          }
        })
      }       
    },
    
  }
</script>

@endsection
