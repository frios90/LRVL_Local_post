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
        <p class="text-muted mb-0 hover-cursor">fotos</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Fotos del Producto [ {{ product.name }} ]</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <router-link to="/mono-company-products">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link> 
            </div>
          </div> 
      </div>

      <div class="card">        
        <div class="card-body">            
          <form enctype="multipart/form-data">
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="input-file-photo" name="filename" id="inputFileUpload" v-on:change="onFileChange" />
              </div>
              <div class="input-group-append">
                <button class="btn-approve-item float-right mdi mdi-check" @click="submitFile"></button>
              </div>
            </div>            
          </form>
          <br> 
          <span v-if="error_photo" class="errors errors-upload-img">{{ error_photo }}</span>
          <div v-for="(photo, key) in photos" :key="key" class="grid-photo">
            <div class="grid-photo-div1">
                <viewer :options="viewer_options" :images="['../../' + photo.path]">
                  <img v-for="src in [photo.path]" :src="'../../' + src" :key="src" width="100" height="80" />
                </viewer>
            </div>
            <div class="grid-photo-div2">
                <span class="span-key">Archivo:  </span> <span class="span-value"> {{ photo.name }}          </span> <br>
                <span class="span-key">Original: </span> <span class="span-value"> {{ photo.original_name }} </span> <br>
                <span class="span-key">Tamaño:   </span> <span class="span-value"> {{ photo.size }}          </span> <br> 
            </div>
            <div class="grid-photo-div3">                
                <span class="span-key">Fecha:</span>  <span class="span-value">{{ photo.created_at | dateInverted }} </span> <br>
                  <span class="span-key">Principal:</span> <span class="span-value">{{ photo.principal > 0 ? 'Si' : 'No' }} </span> <br>
                <button class="btn-delete-img-galery float-right mdi mdi-close-octagon" title="Presione para quitar imagen de la galería" @click="deletePhoto(photo.id)"></button>
                <button class="btn-principal-img-galery float-right mdi mdi-check" title="Presione para seleccionar como imagen principal" @click="selectPrincipalPhoto(photo.id)"></button>
            </div> 
          </div>
        </div>
      </div>

       
    </div>   
</template>
<script>
  import Util from '../../mixins/Util.js'
  import axios from "axios"

  export default {
    mixins: [Util],
        data () {
          return {
            errors    : {},
            product : {
              id        : this.$route.params.id,
              _token         : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              barcode        : "",
              name           : "",
              description    : "",
              brand_id       : '',
              category_id    : '',
              subcategory_id : '',
            },
            photos         : [],
            filename       : "",
            file           : "",
            error_photo  : "",
            viewer_options : {
                          toolbar: false
                        },
           
            
          }
        },       
        created() {       
          this.getData()         
        },
        methods: {          
          getData () {
              var self = this
              this.$http.get('/get-mono-company-products-to-upload-photos', {params: {id: self.product.id}})
              .then(response => {    
                  self.product.barcode = response.body.barcode
                  self.product.name    = response.body.name
                  self.photos          = response.body.images
                  self.loader = false
              }, response => {    
                  self.loader = false             
                  self.$toasted.global.APP_GENERAL_ERROR()
              })
          },     
          onFileChange(e) {
            this.filename = "Archivo seleccionado: " + e.target.files[0].name
            this.file = e.target.files
          },
          submitFile(e) {
            e.preventDefault()
            let self     = this
            let formData = new FormData()
            formData.append("file", this.file[0])
            formData.append("product_id", this.product.id)
            formData.append("_token", this.product._token)
            this.loader = true
            axios
              .post("/mono-company-products-store-photo", formData)
              .then(function(response) {
                self.loader = false
                if (response.data.error) {
                  self.error_photo = response.data.error
                } else {
                  self.error_photo = ""
                  self.$toasted.global.APP_GENERAL_SUCCESS()
                  self.filename = ""
                  self.file = ""                  
                  self.getData()
                  document.getElementById("inputFileUpload").value = ""
                }                
              })
              .catch(function(error) {
                self.loader = false
                self.output = error
              })
          },
          deletePhoto (photo) {
            var data = {
              _token   : this.csrf,
              photo_id : photo
            }
            this.loader = true
            var self = this
            this.$http.post("/mono-company-products-delete-photo", data).then(
              response => {
                self.loader = false
                if (response.body.error) {
                  self.error_photo = response.error
                }
                self.getData()
              },
              response => {
                self.loader = false
                if (response.status === 422) {
                  self.$toasted.global.APP_GENERAL_ERROR_FORM()
                  self.errors = response.body.errors
                }  else {
                  self.$toasted.global.APP_GENERAL_SUCCESS()
                  self.getData()
                }
              }
            )
          },      
          selectPrincipalPhoto (photo) {
            var data = {
              _token: this.csrf,
              photo_id   : photo,
              product_id : this.product.id
            }
            this.loader = true
            var self = this
            this.$http.post("/mono-company-products-principal-photo", data).then(
              response => {
                self.loader = false
                if (response.body.error) {
                  self.error_photo = response.error
                } else {
                  self.$toasted.global.APP_GENERAL_SUCCESS()
                  self.getData()
                }                
              },
              response => {
                self.loader = false
                if (response.status === 422) {
                  self.$toasted.global.APP_GENERAL_ERROR_FORM()
                  self.errors = response.body.errors
                } else {
                  self.$toasted.global.APP_GENERAL_ERROR()
                }
              }
            )
          },
      }
    }
</script>

@endsection
<style>
  .grid-photo {
    display: grid;
    grid-template-columns: 0.5fr repeat(2, 1fr);
    grid-template-rows: 1fr;
    grid-column-gap: 0px;
    grid-row-gap: 0px;
    margin-bottom: 5px;
  }
  .grid-photo-div1 { grid-area: 1 / 1 / 2 / 2; }
  .grid-photo-div2 { grid-area: 1 / 2 / 2 / 3; }
  .grid-photo-div3 { grid-area: 1 / 3 / 2 / 4; } 

  .errors-upload-img {
    color: red;
    font-size : 11px;
  }
</style>