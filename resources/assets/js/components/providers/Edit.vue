<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
          <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/providers">               
          <p class="text-muted mb-0 hover-cursor">&nbsp;proveedores&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">editar</p>             
      </div>  
      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Editar Proveedor [ {{ provider.name }} ]</h2>         
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para actualizar la información" @click="postUpdate">
            <router-link to="/providers">
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
                  <label>Rut</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Ingrese el rut del proveedor"></span>
                    <input type="text" class="form-control form-control-sm" v-model="provider.rut" name="rut" v-rut:live maxlength="12" readonly >  
                  <span class="errors" v-if="errors && errors.rut">{{ errors.rut[0] }}</span>               
                </div>
                <div class="form-group col-md-6">
                  <label>Nombre</label>
                  <span class="mdi mdi-alert-circle circle-info" title="ingrese el nombre competo del proveedor"></span>
                  <input type="text" class="form-control form-control-sm" v-model="provider.name" name="name" maxlength="50">
                  <span class="errors" v-if="errors && errors.name">{{ errors.name[0] }}</span>               
                </div> 
              </div>               
              <div class="row">
                <div class="form-group col-md-6">
                  <label>Correo</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Ingrese el correo de contacto del proveedor"></span>
                  <input type="text" class="form-control form-control-sm" v-model="provider.email" name="email" maxlength="50" >       
                  <span class="errors" v-if="errors && errors.email">{{errors.email[0] }}</span>               
                </div> 

                <div class="form-group col-md-6">
                  <label>Teléfono</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Ingrese el teléfono de contacto del proveedor"></span>
                  <input type="text" class="form-control form-control-sm" v-model="provider.phone" name="phone" maxlength="10" @keypress="isNumber($event)" >       
                  <span class="errors" v-if="errors && errors.phone">{{errors.phone[0] }}</span>               
                </div>                
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                    <label for="">Región</label>
                     <multiselect 
                        v-model="provider.region_id"
                        :value="'Atacama'" 
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
                    <label for="">Comuna</label>
                     <multiselect 
                        v-model="provider.commune_id"                        
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
                    <label for="">Dirección <i class="mdi mdi-alert-circle circle-info" title="Ingrese la dirección del proveedor"></i></label>
                    <input type="text" class="form-control form-control-sm  mantainer-input" v-model="provider.address" name="address" maxlength="50">
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
          provider : {
            id        : this.$route.params.id,
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            rut        : "",
            name       : "",
            email      : "",
            phone      : "",
            region_id  : "",
            commune_id : "",
            address    : "", 
            post_event : "update"  
          },
          region_list  : [],
          commune_list : [],          
        }
      },       
      created() {       
        this.getData()
        this.getRegions()      
      },
    methods: {
      async getRegions() {
        var self = this
        await this.$http.get('/region-list').then(function(response) {
            self.region_list = response.body
            self.region_list.forEach(function(val, ind){
              if (val.id ==  self.provider.region_id) {
                 self.provider.region_id = val
              }
            })
        }, function() {
            self.$toasted.global.APP_GENERAR_ERROR()
        })
      },      
      async getCommunes() {
        this.commune_id = ''
        this.loader = false
        var self = this
        await this.$http.get('/commune-list', {
            params: {
                region: self.provider.region_id
            }
        }).then(function(response) {
            self.loader = false
            self.commune_list = response.body
            self.commune_list.forEach(function(val, ind){
              if (val.id ==  self.provider.commune_id) {
                self.provider.commune_id = val
              }
            })  
        }, function() {
            self.loader = false
            self.$toasted.global.APP_GENERAR_ERROR()
        })
      }, 
      async getData () {
        var self = this
        await this.$http.get('/get-provider', {params: {id: self.provider.id}})
        .then(response => {                    
          self.provider.rut   = response.body.rut
          self.provider.name  = response.body.name
          self.provider.email = response.body.email
          self.provider.phone = response.body.phone
          self.provider.address    = response.body.address
          self.provider.region_id  = response.body.region_id
          self.getCommunes()
          self.provider.commune_id = response.body.commune_id
          self.loader = false
        }, response => {    
          self.loader = false             
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      },            
      async postUpdate () {
        event.preventDefault()                
        var self = this  
        this.provider.region_id  = this.provider.region_id ? this.provider.region_id.id : ''
        this.provider.commune_id = this.provider.commune_id ? this.provider.commune_id.id : '' 
        await this.$http.post('/provider-store', this.provider)
        .then(response => {
          self.$toasted.global.APP_GENERAL_SUCCESS()
          self.errors = {}
          self.$router.push({ path: "/providers"})
        }, response => {     
           self.region_list.forEach(function(val, ind){
            if (val.id ==  self.provider.region_id) {
                self.provider.region_id = val
            }
            
          })  
          self.commune_list.forEach(function(val, ind){
              if (val.id ==  self.provider.commune_id) {
                 self.provider.commune_id = val
              }
            })             
          if (response.status === 422) {
            self.$toasted.global.APP_GENERAL_ERROR_FORM()
            self.errors = response.body.errors
          }
        })
      }          
    }
  }
</script>

@endsection
