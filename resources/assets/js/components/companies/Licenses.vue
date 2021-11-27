<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
       <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/companies">               
            <p class="">&nbsp;compañias&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">asignación de licencias</p>             
      </div>  

      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Asignación de licencias a la empresa [ {{ company.name }} ] </h2>         
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
      <div class="card">          
        <div class="card-body">            
            <div class="row">
              <div class="div-parent-menu title-add-license-row">
                Licencias de reportes
              </div>
              <div v-for="(license, key_report) of licenses.REPORT" :key="key_report" class="col-md-12" >
                <div >
                   <div class="div-submenu-menu" :class="setStyleSelectedLicense(license)">
                      <input class="checkbox-right" type="checkbox" v-model="license.checked" @change="postAddLicense(license)">     

                      {{ license.data.label }} 
                      [<span class="span-description-menu">{{license.data.description}}</span>] 
                    </div>  
                </div>    
              </div>
            </div>
            <div class="row">
              <div class="div-parent-menu title-add-license-row">
                Licencias de especiales
              </div>
              <div v-for="(license, key_special) of licenses.SPECIAL" :key="key_special" class="col-md-12" >
                <div >
                   <div class="div-submenu-menu" :class="setStyleSelectedLicense(license)">
                      <input class="checkbox-right" type="checkbox" v-model="license.checked" @change="postAddLicense(license)">     

                      {{ license.data.label }} 
                      [<span class="span-description-menu">{{license.data.description}}</span>] 
                    </div>  
                </div>    
              </div>
            </div>
            <div class="row">
              <div class="div-parent-menu title-add-license-row">
                Licencias de módulo
              </div>
              <div v-for="(license, key_module) of licenses.MODULE" :key="key_module" class="col-md-12" >
                <div >
                   <div class="div-submenu-menu" :class="setStyleSelectedLicense(license)">
                      <input class="checkbox-right" type="checkbox" v-model="license.checked" @change="postAddLicense(license)">     

                      {{ license.data.label }} 
                      [<span class="span-description-menu">{{license.data.description}}</span>] 
                    </div>  
                </div>    
              </div>
            </div>
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
          errors : {}, 
          company : {
            id : this.$route.params.id,
            name: ""
          },
          licenses: []    
          
        }
      },       
      mounted () {       
        this.initData()
      },
      methods: {
        async initData () {
          await this.getCompany()
          await this.getLicenses()
          this.loader = false
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
        getLicenses () {
          var self = this
          this.$http.get('/company-licenses-format-box',{params: {id: self.company.id}})
          .then(response => { 
            console.log(response.body.REPORT) 
            self.licenses = response.body    
          }, response => {                 
            self.$toasted.global.APP_GENERAL_ERROR()
          })
        },
        postAddLicense (license) {
          event.preventDefault() 
          this.loader=true 
          var send = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            license: license,
            company_id: this.company.id
          }    
          var self = this  
          this.$http.post('/company-license-store', send)
          .then(response => {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.getLicenses()
              self.loader=false 
            }, response => {
                self.loader=false                  
                if (response.status === 422) {
                  self.$toasted.global.APP_GENERAL_ERROR_FORM()
                  self.errors = response.body.errors
                }else{
                  self.$toasted.global.APP_GENERAL_ERROR()
                }
            })
        },
        setStyleSelectedLicense (license) {
          if (license.checked) {
            return "div-license-checked"
          }
        }     
      },
     
    
  }
</script>
<style>
  .span-description-menu {
    color:#1d3344!important;
  }

  .div-license-checked {
    background:  rgba(49,79,86,1);
    background: -webkit-linear-gradient(45deg, rgba(49,79,86,1),  rgba(170,246,193,1));
    background: -o-linear-gradient(45deg, rgba(49,79,86,1),  rgba(170,246,193,1));
    background: -moz-linear-gradient(45deg, rgba(49,79,86,1),  rgba(170,246,193,1));
    background: linear-gradient(45deg, rgba(49,79,86,1),  rgba(170,246,193,1));

    color: #fff;
  }

  .title-add-license-row {
    width: 100%!important;
  }
</style>
@endsection
