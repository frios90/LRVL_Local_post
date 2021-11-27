<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
       <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/companies">               
            <p class="">empresas&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">asignación de productos</p>             
      </div>  

      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Asignación de productos a la empresa {{ company.name }} </h2>         
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
             <router-link to="/companies">
              <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para volver al menu anterior">
            </router-link> 
          </div>
        </div> 
      </div>  
      <hr>  
      <form class="form-basic">
          <div class="row div-filters-allocation">
        <div class="form-group col-md-4">
          <label>Categoría</label>
            <span class="mdi mdi-alert-circle circle-info" title="Filtre seleccionando una categoría"></span>
            <select class="select-css" v-model="filters.category_id" @change="getSubcategories()">
                <option value="">Todas</option>
              <option v-for="(category, key_categories) of categories" :key="key_categories" :value="category.id">{{category.code}}</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label>Subcategoría</label>
            <span class="mdi mdi-alert-circle circle-info" title="Filtre seleccionando una subcategoría"></span>
            <select class="select-css" v-model="filters.subcategory_id" request @change="getProducts()">
              <option value="">Todas</option>
              <option v-for="(subcategory, key_subcategories) of subcategories" :key="key_subcategories" :value="subcategory.id">{{subcategory.code}}</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label>Marca</label>
            <span class="mdi mdi-alert-circle circle-info" title="Filtre seleccionando una marca"></span>
            <select class="select-css" v-model="filters.brand_id" name="brand_id" @change="getProducts()">
                <option value="">Todas</option>
              <option v-for="(brand, key_brands) of brands" :key="key_brands" :value="brand.id">{{brand.code}}</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label>Nombre</label>
            <span class="mdi mdi-alert-circle circle-info" title="Filtre ingresando el nombre del producto"></span>
            <input type="text" class="form-control form-control-sm" v-model="filters.name" name="name" @keyup="getProducts()">
          </div>
          <div class="form-group col-md-4">
            <label>Código de barras</label>
            <span class="mdi mdi-alert-circle circle-info" title="Filtre ingresando el código de barras"></span>
            <input type="text" class="form-control form-control-sm" v-model="filters.barcode" name="barcode" @keyup="getProducts()">  
          </div>
          <div class="form-group col-md-4">
            <label>Asignar todos los elementos de la busqueda</label>
            <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para asignar todos lo elementos de la busqueda" @click="allocationAll()">
          </div>
      </div>    
      </form>   
      <hr>   
      <div class="row">
        <div v-for="(product, key_product) of products" :key="key_product" class="col-md-3 col-sm-6">
          <div class="card-product-allocation" :class="styleAllocationHas(product.check)" 
                  @click="postAllocation(product)">
            <div class="div-allo-brand">{{product.brand}}</div>     
            <div class="img-product-allo">
              <img  v-if="product.main_image" :src="'/'+product.main_image.path" width="100" alt="">
              <img  v-else src="/intranet/img/icons/camara-fotografica.png" alt="" width="100">
            </div>
            <div>
              <div class="div-allo-barco">{{product.barcode}}</div>
              <div class="div-allo-label">{{product.name}}</div>                                    
              <div class="div-allo-category">[ {{product.category}} ]</div>
              <div class="div-allo-sub">[ {{product.subcategory}} ]</div>
              
            </div>
            <div class="info-product-allocate">
              {{product.description}}
            </div>
          </div>
          
          
          
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
          errors : {}, 
          company : {
            id : this.$route.params.company_id,
            name: ""
          },
          filters: {
            company_id : this.$route.params.company_id,
            category_id: '',
            brand_id: '',
            subcategory_id: '',
            barcode: '',
            name: ''
          },
          products: [],
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
          await this.getCompany()
          await this.getProducts()
          await this.getCategories()
          await this.getBrands()
          this.loader = false
        },  
        styleAllocationHas(check) {
          if (check) {
            return "div-has-allocation"
          } 
        },
        getCompany () {
            var self = this
            this.$http.get('/get-company', {params: {id: self.company.id}})
            .then(response => {                    
              self.company.name = response.body.name    
            }, response => {                 
              self.$toasted.global.APP_GENERAL_ERROR()
            })
        },  
        getProducts () {
          var self = this
          this.loader = true
          this.$http.get('/get-products-to-allocation', {params: self.filters})
          .then(response => {  
            self.products = response.body  
            self.loader = false 
          }, response => {                 
            self.$toasted.global.APP_GENERAL_ERROR()
          })
        },
        postAllocation (product) {
          event.preventDefault() 
          this.loader = true 
          var send = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            product: product,
            company: this.company.id
          }    
          var self = this  
          this.$http.post('/company-product-allocation-store', send)
          .then(response => {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.loader = false
              self.getProducts()
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
            console.log('response.body')  
              self.brands = response.body                       
          }, response => {                 
              self.$toasted.global.APP_GENERAL_ERROR()
          })
        },  
        getCategories () {
          var self = this  
          this.$http.get('/get-code-list-for-type', {params : {type:"PRODUCT_CATEGORY"}})
          .then(response => {
            console.log(response.data)
              self.categories = response.data
            }, response => {                 
                self.mixinGenericError(response)
            })
        },
        getSubcategories () {
          var self = this  
          this.$http.get('/get-categories-subcategories', {params: {category_id: this.filters.category_id}})
          .then(response => {
            console.log(response.data)
              self.subcategories = response.data
              self.filters.subcategory_id = ''
            }, response => {                 
                self.mixinGenericError(response)
            })
            this.getProducts()
        },
        allocationAll () {
          event.preventDefault()  
          var send = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            filters: this.filters,
            company: this.company.id
          }    
          console.log(send)
          var self = this  
          this.$http.post('/company-product-allocation-all-store', send)
          .then(response => {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.getProducts()
            }, response => {                 
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

  .card-product-allocation {
    cursor: pointer;
    margin: 6px -4px;
    padding: 10px;
    border-radius: 10px 10px 10px 10px;
    -moz-border-radius: 10px 10px 10px 10px;
    -webkit-border-radius: 10px 10px 10px 10px;
    border: 0px solid ;
    height: 250px;
    -webkit-box-shadow: 5px 5px 5px 0px rgba(219, 229, 255, 0.75);
    -moz-box-shadow: 5px 5px 5px 0px rgba(219, 229, 255, 0.75);
    box-shadow: 5px 5px 5px 0px rgba(219, 229, 255, 0.75);
    -webkit-transform:scale(0.6);transform:scale(0.6);
    }

  .card-product-allocation:hover {   
    -webkit-transform:scale(1.1);transform:scale(1.1);
    transition: 0.3s;
    }

  .div-has-allocation {
    background:  rgb(97, 153, 165);
    background: -webkit-linear-gradient(45deg, rgb(97, 153, 165),  rgba(170,246,193,1));
    background: -o-linear-gradient(45deg, rgb(97, 153, 165),  rgba(170,246,193,1));
    background: -moz-linear-gradient(45deg, rgb(97, 153, 165),  rgba(170,246,193,1));
    background: linear-gradient(45deg, rgb(97, 153, 165),  rgba(170,246,193,1));
  }

  .img-product-allo img{
    width: 100%;
    height: 100px;
    padding: 0px 50px;
  }

  .div-allo-barco {
    margin-top: 10px;
    font-size: 10px;
    text-align: center;
    color: rgb(97, 153, 165);

  }
   .div-allo-label {
    font-size: 14px;
    color: rgb(97, 153, 165);
    font-weight: 600;
    text-align: center;
    margin-bottom: 5px;
  }
  .div-allo-category {
    font-size: 8px;
    text-align: center;
    margin-bottom: 5px;
    color: rgb(97, 153, 165);
    font-weight: 600;
  }
  .div-allo-sub {
    font-size: 8px;
    text-align: center;
    margin-bottom: 5px;
    color: rgb(97, 153, 165);
    font-weight: 600;
  }
  .div-allo-brand {
    font-size: 10px;
    text-align: right;
    margin-bottom: 5px;
    color: rgb(97, 153, 165);
    font-weight: 600;
  }

  .div-has-allocation .div-allo-barco {
    
    color: #fff;

  }
  .div-has-allocation  .div-allo-label {
   
    color: #fff;
    
  }
  .div-has-allocation .div-allo-category {
   
    color: #fff;
  }
  .div-has-allocation .div-allo-sub {
   
    color: #fff;
  }
  .div-has-allocation .div-allo-brand {
  
    color: #fff;

  }

  .info-product-allocate {
    display: none;
    position: absolute;
    margin-left: -10px;
    bottom: 0;
    z-index: 10;
    background: rgba(0, 0, 0, 0.5);
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#50000000,endColorstr=#50000000);
     width: calc(100% -100px);
     color: #FFF;
     font-size: 10px;
     text-align: justify;
opacity: 0;
     padding: 10px;
    transition: opacity 1s ease-out!important; 

}

    .card-product-allocation:hover .info-product-allocate {
    display: block;
    opacity: 1;
transition: opacity 1s ease-out!important;    
}
</style>
@endsection
