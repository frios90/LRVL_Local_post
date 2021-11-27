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
        <p class="text-muted mb-0 hover-cursor">ver</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Detalle del Producto [ {{ product.name }} ]</h2>          
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
      <form class="form-basic">       
        <div class="row">
          <div class="form-group col-md-6">                
            <div class="div-product-img-main">
                <img v-if="product.image_main" :src="'/' + product.image_main.path" class="avatar-my" width="100%" height="250px" alt="">
            </div> 
            <div class="div-viewer">
              <viewer :options="viewer_options" :images="product.images">
                <img v-for="src in product.images" :src="'/' +src" :key="src" width="100" height="80" class="viewer-img"/>
              </viewer>  
            </div>                             
          </div>
          <div class="form-group col-md-6">
            <div>
              <div class="div-brand">{{product.brand.label}}</div>
              <div class="div-name">{{product.name}}</div>
              <div class="div-barcode"><span class="label">código: </span> <span class="code">{{product.barcode}}</span></div>
              <div class="div-stock"><span class="label">stock activo: </span> <span class="code">{{product.qty_active}}</span></div>
              <div class="div-price"><span class="label">precio: </span> <span class="code">{{product.price | currency}}</span></div>
              <div class="div-category"><span class="label">categoría: </span> <span class="code"  v-if="product.subcategory.parent">{{product.subcategory.parent.label}}</span></div>
              <div class="div-subcategory"><span class="label">sub-categoría: </span> <span class="code" >{{product.subcategory.label}}</span></div>
              <div class="div-description">{{product.description}}</div>
            </div>
          </div>
        </div>
      </form>
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
          brand          : {},
          subcategory    : {},
          qty_active     : 0,
          price     : 0,
          images         : [],
          image_main     : ""
        },
        categories    : [],
        brands        : [],
        viewer_options : {
                          toolbar: false
                        },
      }
    },       
    created() {       
      this.getData()
    },  
    mounted () {
    },    
    methods: {     
      getData () {
        var self = this
        this.$http.get('/get-mono-company-products', {params: {id: self.product.id}})
        .then(response => {  
          self.product.barcode     = response.body.company_product.product.barcode
          self.product.name        = response.body.company_product.product.name
          self.product.description = response.body.company_product.product.description
          self.product.brand       = response.body.company_product.product.brand
          self.product.subcategory = response.body.company_product.product.category
          self.product.qty_active  = response.body.company_product.qty
          self.product.price       = response.body.company_product.price
          self.product.images      = response.body.array_images
          self.product.image_main  = response.body.image_main
          self.loader = false          
        }, response => {                 
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      },
  
           
    },
  }
</script>
<style >
  .div-product-img-main {
    padding: 0px 100px;
  }
  .div-brand {
    color: #2d363e!important;
    font-size: 11px!important;
  }
  .div-name {
    color: #2d363e!important;
    font-size: 20px!important;
  }

  .div-barcode .label{
    color: #2d363e!important;
    font-size: 10px!important;
  }
  .div-barcode .code{
    color: #3cbc64!important;
    font-size: 18px!important;
  }
  .div-stock .label{
    color: #2d363e!important;
    font-size: 10px!important;
  }
  .div-stock .code{
    color: #3cbc64!important;
    font-size: 14px!important;
  }
   .div-price .label{
    color: #2d363e!important;
    font-size: 10px!important;
  }
  .div-price{
    margin: 10px 0px;
  }
  .div-price .code{
    color: #3cbc64!important;
    font-size: 14px!important;
  }
   .div-category .label{
    color: #2d363e!important;
    font-size: 10px!important;
  }
  .div-category .code{
    color: #3cbc64!important;
    font-size: 16px!important;
  }
   .div-subcategory .label{
    color: #2d363e!important;
    font-size: 10px!important;
  }
  .div-subcategory .code{
    color: #3cbc64!important;
    font-size: 14px!important;
  }

  .div-description {
    text-align: justify;
    margin-top: 25px;
    color: #2d363e!important;
    font-size: 12px!important;
  }
  .div-viewer {
    margin: auto!important;
  }
  .viewer-img {
    margin: 10px;
  }
</style>
@endsection
