<template >
    <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>     

      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/companies">               
            <p class="text-muted mb-0 hover-cursor">&nbsp;empresas&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">crear</p>             
      </div>  

      <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Registro de una nueva empresa</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para Guardar el nuevo registro." @click="postStore">
              <router-link to="/companies">
                <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
              </router-link> 
            </div>
          </div> 
      </div>
      <hr>                  
      <div class="">          
        <div class="card-body">            
           <form action="" class="form-basic">                    
              <div class="row">
                  <div class="form-group col-md-4">
                      <label for="">Rut <i class="mdi mdi-alert-circle circle-info" title="Ingrese un rut valido para la empresa"></i></label>
                      <input type="text" class="form-control form-control-sm  mantainer-input" v-model="company.rut" name="rut" v-rut:live maxlength="12">
                      <span class="errors" v-if="errors && errors.rut">{{errors.rut[0] }}</span>
                  </div>                        
                  <div class="form-group col-md-4">
                      <label for="">Nombre <i class="mdi mdi-alert-circle circle-info" title="Ingrese el nombre de la empresa"></i></label>
                      <input type="text" class="form-control form-control-sm  mantainer-input"  v-model="company.name" maxlength="35" name="name">
                      <span class="errors" v-if="errors && errors.name">{{errors.name[0] }}</span>
                  </div>
              </div>
              <div class="row">
                  <div class="form-group col-md-4">
                      <label for="">Contacto <i class="mdi mdi-alert-circle circle-info" title="Nombre del contacto directo con la empresa"></i></label>
                      <input type="text" class="form-control form-control-sm  mantainer-input"  v-model="company.contact" name="contact" maxlength="50">
                      <span class="errors" v-if="errors && errors.contact">{{errors.contact[0] }}</span>
                  </div>
                  <div class="form-group col-md-4">
                      <label for="">teléfo de contacto <i class="mdi mdi-alert-circle circle-info" title="Número de teléfono del contacto directo con la empresa"></i></label>
                      <input type="text" class="form-control form-control-sm  mantainer-input" v-model="company.phone" name="phone" maxlength="9" @keypress="isNumber($event)">
                      <span class="errors" v-if="errors && errors.phone">{{errors.phone[0] }}</span>
                  </div>
                  <div class="form-group col-md-4">
                      <label for="">Correo de contacto <i class="mdi mdi-alert-circle circle-info" title="Correo del contacto directo de la empresa"></i></label>
                      <input type="text" class="form-control form-control-sm  mantainer-input" v-model="company.email" name="email" maxlength="35">
                      <span class="errors" v-if="errors && errors.email">{{errors.email[0] }}</span>
                  </div>                                           
              </div>                                       
              <div class="row">
                  <div class="form-group col-md-4">
                  <label for="">Región </label>                   
                  <multiselect 
                      v-model="company.region_id" 
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
                      v-model="company.commune_id" 
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
                      <label for="">Dirección <i class="mdi mdi-alert-circle circle-info" title="Ingrese la dirección en la que se ubica la empresa"></i></label>
                      <input type="text" class="form-control form-control-sm  mantainer-input" v-model="company.address" name="address">
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
        company : {
          _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
           rut: "",
          name: "",
          email: "",
          phone: "",
          address: "",               
          commune_id: "",
          region_id: "",
          contact: "",
          post_event : "store"  
        },
        profiles     : [],
        companies    : [],
        region_list: [],
        commune_list: [],
      }
    },  
    created() {
      this.getRegions()        
    },
    methods: {   
      getRegions() {
        var self = this
        this.$http.get('/region-list').then(function(response) {
            self.region_list = response.body
            self.loader = false
        }, function() {
            this.$toasted.global.APP_GENERAR_ERROR()
        })
      },      
      getCommunes() {
        this.commune_id = ''
        var self = this
        this.loader = true
        this.$http.get('/commune-list', {
            params: {
                region: self.company.region_id
            }
        }).then(function(response) {
          self.loader = false
          self.commune_list = response.body
        }, function() {
          self.loader = false
          this.$toasted.global.APP_GENERAR_ERROR()
        })
      },    
      postStore () {
        event.preventDefault()
        this.loader = true      
        var self = this 
        this.company.region_id  = this.company.region_id ? this.company.region_id.id : ''
        this.company.commune_id = this.company.commune_id ? this.company.commune_id.id : ''
        this.$http.post('/company-store', this.company)
        .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            self.$router.push({ path: "/companies"})
          }, response => {              
              self.laoder = false   
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
              if (response.status === 422) {
                self.$toasted.global.APP_GENERAL_ERROR_FORM()
                self.errors = response.body.errors
              }else{
                self.$toasted.global.APP_GENERAL_ERROR()
              }
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
