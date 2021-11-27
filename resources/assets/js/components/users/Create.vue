<template >
    <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/users">               
            <p class="text-muted mb-0 hover-cursor">&nbsp;usuarios&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">crear</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Registro de nuevo Usuario</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  :title="msg_save_store" @click="postStore">
              <router-link to="/users">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  :title="msg_return_menu">
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
                  <label>Rut</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Debe ingresar un rut valido"></span>
                    <input type="text" class="form-control form-control-sm" v-model="user.rut" name="rut" v-rut:live maxlength="12" >  
                  <span class="errors" v-if="errors && errors.rut">{{ errors.rut[0] }}</span>               
                </div>
                <div class="form-group col-md-6">
                  <label>Nombre Completo</label>
                  <input type="text" class="form-control form-control-sm" v-model="user.name" name="name" >
                  <span class="errors" v-if="errors && errors.name">{{ errors.name[0] }}</span>               
                </div> 
              </div>               
              <div class="row">
                <div class="form-group col-md-6">
                  <label>Correo</label>
                  <span class="mdi mdi-alert-circle circle-info" title="El correo ingresado será con el cual el usuario ingresará a la aplicación"></span>
                  <input type="text" class="form-control form-control-sm" v-model="user.email" name="email" >       
                  <span class="errors" v-if="errors && errors.email">{{errors.email[0] }}</span>               
                </div>  
                <div class="form-group col-md-6">
                  <label>Teléfono</label>
                  <input type="text" class="form-control form-control-sm" v-model="user.phone" name="phone" maxlength="9" @keypress="isNumber($event)">       
                  <span class="errors" v-if="errors && errors.phone">{{errors.phone[0] }}</span>               
                </div>              
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label>Empresa</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Seleccione la empresa a la que pertenece el usuario"></span>
                  <select class="select-css" v-model="user.company_id" name="company_id">
                    <option v-for="(company, key_companies) of companies" :key="key_companies" :value="company.id">{{company.name}}</option>
                  </select>
                  <span class="errors" v-if="errors && errors.company_id">{{ errors.company_id[0] }}</span>               
                </div>    
                <div class="form-group col-md-6">
                  <label>Perfil</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Seleccione el perfil o rol que tendrá el usuario en la empresa"></span>
                  <multiselect 
                      v-model="user.profile_id" 
                      deselect-label="" 
                      selected-label="" 
                      selectLabel=""
                      track-by="label" 
                      label="label" 
                      placeholder="" 
                      noOptions="Sin resultados"
                      :options="profiles" 
                      :searchable="true" 
                      :allow-empty="false"                      
                    >
                    <template slot="singleLabel" slot-scope="{ option }">
                      <strong>{{ option.label }}</strong>
                    </template>
                    <template v-slot:noOptions>
                        No hay resultados.
                    </template>
                    <template v-slot:noResult>
                        No hay resultados.
                    </template>
                  </multiselect>
                  <span class="errors" v-if="errors && errors.profile_id">{{ errors.profile_id[0] }}</span>               
                </div>                           
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="">Región </label>                   
                  <multiselect 
                      v-model="user.region_id" 
                      deselect-label="" 
                      selected-label="" 
                      selectLabel=""
                      track-by="name" 
                      label="name" 
                      noOptions="Sin resultados"
                      placeholder="" 
                      :options="region_list" 
                      :searchable="true" 
                      :allow-empty="false"
                      @input="getCommunes()"

                    >
                    <template slot="singleLabel" slot-scope="{ option }">
                      <strong>{{ option.name }}</strong>
                    </template>
                    <template v-slot:noOptions>
                        No hay resultados.
                    </template>
                    <template v-slot:noResult>
                        No hay resultados.
                    </template>
                  </multiselect>  
                  <span class="errors" v-if="errors && errors.region_id">{{errors.region_id[0] }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Comuna </label>
                    <multiselect 
                      v-model="user.commune_id" 
                      deselect-label="" 
                      selected-label="" 
                      selectLabel=""
                      track-by="name" 
                      label="name"                      
                      placeholder="" 
                      :options="commune_list"                       
                      :searchable="true" 
                      :allow-empty="false"
                    >
                    <template slot="singleLabel" slot-scope="{ option }">
                      <strong>{{ option.name }}</strong>
                    </template>
                    <template v-slot:noOptions>
                        No hay resultados.
                    </template>
                    <template v-slot:noResult>
                        No hay resultados.
                    </template>
                  </multiselect>                   
                    <span class="errors" v-if="errors && errors.commune_id">{{errors.commune_id[0] }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Dirección </label>
                    <input type="text" class="form-control form-control-sm  mantainer-input" maxlength="50" v-model="user.address" name="address">
                    <span class="errors" v-if="errors && errors.address">{{errors.address[0] }}</span>
                </div>
            </div>              
          </form>
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
        errors    : {},
        user : {
          _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          rut        : "",
          name       : "",
          email      : "",
          phone      : "",
          profile_id : "",
          profiles   : [],
          company_id : "",
          region_id  : "",
          commune_id : "",
          address    : "",
          post_event : "store"  
        },
        profiles     : [],
        companies    : [],
        region_list  : [],
        commune_list : [],

      }
    },  
    mounted () {
       this.initData()
    },    
    methods: { 
       async initData () {
        await this.getProfiles()
        await this.getCompanies()
        await this.getRegions()
        this.loader = false
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
      getProfiles () {
        var self = this
        this.$http.get('/get-code-list-for-type', {params : {type:"PROFILES"}})
        .then(response => {    
            self.profiles = response.body                       
        }, response => {                 
            self.$toasted.global.APP_GENERAL_ERROR()
        })
      },   
      getCompanies () {
        var self = this
        this.$http.get('/get-companies-list-selector')
        .then(response => {    
            self.companies = response.body                       
        }, response => {                 
            self.$toasted.global.APP_GENERAL_ERROR()
        })
      },     
      postStore () {       
        event.preventDefault()   
        this.user.profile_id = this.user.profile_id.id
        this.user.region_id  = this.user.region_id.id
        this.user.commune_id = this.user.commune_id.id   
        var self = this  
        this.$http.post('/user-store', this.user)
        .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            self.$router.push({ path: "/users"})
          }, response => {  
             self.profiles.forEach(function(val, ind){
                if (val.id ==  self.user.profile_id) {
                  self.user.profile_id = val
                }
              })  
               self.region_list.forEach(function(val, ind){
            if (val.id ==  self.company.region_id) {
                self.company.region_id = val
            }
            
          })  
          self.commune_list.forEach(function(val, ind){
              if (val.id ==  self.company.commune_id) {
                 self.company.commune_id = val
              }
            })  
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
    filters: {
      upper: function (value) {
          return value.toUpperCase();
      }
    }
  }
</script>
@endsection
