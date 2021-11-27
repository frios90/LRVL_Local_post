<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
          <p class="">inicio&nbsp;/</p>             
        </router-link>        
        <p class="text-muted mb-0 hover-cursor">&nbsp;mi perfil</p>             
      </div>  
      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Datos de mi perfil</h2>         
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para actualizar la información" @click="postUpdate">
          </div>
        </div> 
      </div>   
      <hr> 
      <div class="card">          
        <div class="card-body">            
          <div class="row">
            <div class="col-md-4">
              <form enctype="multipart/form-data">
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" name="filename" class="input-file-photo" id="inputFileUpload" v-on:change="onFileChange" />
                   
                  </div>
                  
                  <div class="input-group-append">
                    <button class="btn-approve-item float-right mdi mdi-check" @click="submitFile"></button>
                  </div>
                </div>
                <div class="msg-picture-able">*Solo archivos .jpg, jpeg</div>
              </form>
              <hr>
              <div class="">
                <img v-if="user.image" :src="'/' + user.image.path" class="avatar-my" width="100%" height="250px" alt="">
                <img v-else src="/intranet/img/icons/024-click.png" class="avatar-my" width="100%" height="250px" alt="">
              </div>
              <hr>
              <div class="fix-detail">
                <div class="detail-profile">{{user.profile}}</div>
                <div class="detail-company">{{user.company}}</div>
                <div class="detail-rut">{{user.rut}}</div>                
              </div>
            </div>
            <div class="col-md-8">
              <form class="form-basic">       
                  <div class="row">
                    <div class="form-group col-md-4">
                      <label>Correo</label>
                      <input type="text" class="form-control form-control-sm" v-model="user.email" name="email" >       
                      <span class="errors" v-if="errors && errors.email">{{errors.email[0] }}</span>               
                    </div>
                    <div class="form-group col-md-4">
                      <label>Nombre</label>
                      <input type="text" class="form-control form-control-sm" v-model="user.name" name="name" >
                      <span class="errors" v-if="errors && errors.name">{{ errors.name[0] }}</span>               
                    </div> 
                    <div class="form-group col-md-4">
                      <label>Teléfono</label>
                      <input type="text" class="form-control form-control-sm" v-model="user.phone" name="phone" maxlength="9" @keypress="isNumber($event)" >
                      <span class="errors" v-if="errors && errors.phone">{{ errors.phone[0] }}</span>               
                    </div> 
                  </div>  
                   <div class="row">
                <div class="form-group col-md-4">
                    <label for="">Región <i class="mdi mdi-alert-circle circle-info" title="Región en la que se ubica el bar o empresa"></i></label>
                    <select class="select-css" v-model="user.region_id" name="region_id" @change="getCommunes()">
                        <option value="0" selected>Seleccione una región</option>
                        <option v-for="(list, index) in region_list" :key="index" :value="list.id">
                            {{ list.name }}
                        </option>
                    </select>
                    <span class="errors" v-if="errors && errors.region_id">{{errors.region_id[0] }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Comuna <i class="mdi mdi-alert-circle circle-info" title="Comuna en la que se ubica el bar o empresa"></i></label>
                    <select class="select-css" v-model="user.commune_id">
                        <option value="0" selected>Seleccione una comuna</option>
                        <option v-for="(list, index) in commune_list" :key="index" :value="list.id" name="commune_id">
                            {{ list.name }}</option>
                    </select>
                    <span class="errors" v-if="errors && errors.commune_id">{{errors.commune_id[0] }}</span>

                </div>
                <div class="form-group col-md-4">
                    <label for="">Dirección <i class="mdi mdi-alert-circle circle-info" title="Direccion del bar o empresa"></i></label>
                    <input type="text" class="form-control form-control-sm  mantainer-input" v-model="user.address" name="address">
                    <span class="errors" v-if="errors && errors.address">{{errors.address[0] }}</span>
                </div>
            </div>   
                  <hr>    
                  <h4>Cambiar Contraseña</h4>
                  <div class="row">                    
                    <div class="form-group col-md-4">
                      <label>Contraseña Actual</label>
                      <input type="text" class="form-control form-control-sm" v-model="user.current_pass" name="current_pass" >       
                      <span class="errors" v-if="errors && errors.current_pass">{{errors.current_pass[0] }}</span>               
                    </div>
                    <div class="form-group col-md-4">
                      <label>Nueva Contraseña</label>
                      <input type="text" class="form-control form-control-sm" v-model="user.new_pass" name="new_pass" >
                      <span class="errors" v-if="errors && errors.new_pass">{{ errors.new_pass[0] }}</span>               
                    </div> 
                     <div class="form-group col-md-4">
                      <label>Repetir Contraseña</label>
                      <input type="text" class="form-control form-control-sm" v-model="user.repeat_pass" name="repeat_pass" >
                      <span class="errors" v-if="errors && errors.repeat_pass">{{ errors.repeat_pass[0] }}</span>               
                    </div> 
                  </div>                   
              </form>
            </div>
          </div>
        </div>
      </div>   
    </div>   
</template>
<script>
  import Util from '../mixins/Util.js'
  import axios from "axios"

  export default {
    mixins: [Util],
      data () {
        return {
          errors    : {},             
          user : {
            id         : "",
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            rut        : "",
            name       : "",
            email      : "",
            profile_id : "",
            company_id : "",
            image      : "",
            region_id  : "",
            phone      : "",
            commune_id : "",
            address    : "",
            current_pass: "",
            new_pass: "",
            repeat_pass: ""
          },
          profiles     : [],
          companies    : [],
          filename     : "",
          file         : "",
          error_photo  : "",
          region_list  : [],
          commune_list : [],
        }
      },       
    created() {       
      this.getData()
      this.getRegions() 
    },
    methods: {    
      onFileChange(e) {
        this.filename = "Archivo seleccionado: " + e.target.files[0].name
        this.file = e.target.files
      },
      submitFile(e) {
        e.preventDefault()
        let self     = this
        let formData = new FormData()
        formData.append("file", this.file[0])
        formData.append("user_id", this.user.id)
        formData.append("_token", this.user._token)
        axios
          .post("/my-user-store-photo", formData)
          .then(function(response) {
            if (response.data.error) {
              self.error_photo = response.data.error
            } else {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.filename = ""
              self.getData()
            }                
          })
          .catch(function(error) {
            self.output = error
          })
      }, 
      getData () {
        var self = this
        this.$http.get('/get-my-profile-data')
        .then(response => { 
          self.user.id         = response.body.id         
          self.user.rut        = response.body.rut
          self.user.name       = response.body.name
          self.user.phone      = response.body.phone
          self.user.email      = response.body.email
          self.user.profile    = response.body.profile.label
          self.user.company    = response.body.company.name
          self.user.image      = response.body.images[0]
          self.user.address    = response.body.address
          self.user.region_id  = response.body.region_id
          self.getCommunes()
          self.user.commune_id = response.body.commune_id
          self.loader = false
        }, response => {                 
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      },  
      getRegions() {
        var self = this
        this.$http.get('/region-list').then(function(response) {
            self.region_list = response.body
        }, function() {
            this.$toasted.global.APP_GENERAR_ERROR()
        })
      },      
      getCommunes() {
        this.commune_id = ''
        var self = this
        this.$http.get('/commune-list', {
            params: {
                region: self.user.region_id
            }
        }).then(function(response) {
            self.commune_list = response.body
        }, function() {
            this.$toasted.global.APP_GENERAR_ERROR()
        })
      },           
      postUpdate () {
        event.preventDefault()                
        var self = this  
        this.$http.post('/change-my-info', this.user)
        .then(response => {
          
          self.errors = ""
          if (response.body.errors) {            
            self.errors = response.body.errors
          } else {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.user.current_pass = ""
            self.user.new_pass     = ""
            self.user.repeat_pass  = ""
            self.errors = {}
            console.log(response.body)
            if (response.body == 'change_pass') {
              self.$toasted.global.CHANGE_PASS_OK()
            }
          }
         
        }, response => {                 
          if (response.status === 422) {
            self.$toasted.global.APP_GENERAL_ERROR_FORM()
            self.errors = response.body.errors
          }
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
  .fix-detail {
    text-align: center;
  }
  .detail-profile {
    color: #2d363e;
    font-size : 12px;
  }
  .detail-company {
    color:#3cbc64!important; 
    font-size : 10px;
    
  }
  .detail-rut {
    color: #2d363e!important;
    font-size : 16px;
    font-weight: 600;
    margin-bottom: 25px;
  }
  .avatar-my {
    border-radius: 10px 10px 10px 10px;
    -moz-border-radius: 10px 10px 10px 10px;
    -webkit-border-radius: 10px 10px 10px 10px;
    border: 0px solid #000000;
    -webkit-box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.75);
    -moz-box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.75);
    box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.75);
  }

h4 {
	color:#3cbc64!important; 
	font-size: 14px!important;
	font-weight: 600;
	letter-spacing: 2px;

}

.msg-picture-able {
  font-size: 11px;
  letter-spacing: 1px;
  font-style: italic;
}
</style>
@endsection
