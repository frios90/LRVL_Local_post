<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
          <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/menus">               
          <p class="text-muted mb-0 hover-cursor">&nbsp;menus&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">editar</p>             
      </div>  
      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Editar Menu [ {{ menu.name }} ]</h2>         
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para actualizar la información" @click="postUpdate">
            <router-link to="/menus">
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
                  <label>Nombre</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Ingrese el Nombre completo de la menu por ingresar"></span>
                  <input type="text" class="form-control form-control-sm" v-model="menu.name" name="name" >
                  <span class="errors" v-if="errors && errors.name">{{ errors.name[0] }}</span>               
                </div> 
                <div class="form-group col-md-6">
                  <label>Icono</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Ingrese el Nombre completo de la menu por ingresar"></span>
                  <input type="text" class="form-control form-control-sm" v-model="menu.path_icon" name="path_icon" readonly>
                  <span class="errors" v-if="errors && errors.path_icon">{{ errors.path_icon[0] }}</span>               
                </div> 
              </div> 
              <div class="row">                                
                <div class="form-group col-md-6">
                  <label>Menu</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Si es un submenu, seleccione el menu al que pertenece"></span>

                  <select class="select-css" v-model="menu.parent_id" request>
                    <option value="" selected>Menu Padre</option>
                    <option v-for="(lmenu, key_menu) of list_menus" :key="key_menu" :value="lmenu.id">{{lmenu.name}}</option>
                  </select>
                  <span class="errors" v-if="errors && errors.parent_id">{{ errors.parent_id[0] }}</span>               
                </div>
                <div class="form-group col-md-6">
                  <label>Ruta</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Ingrese el Nombre completo de la menu por ingresar"></span>
                  <input type="text" class="form-control form-control-sm" v-model="menu.route" name="route" >
                  <span class="errors" v-if="errors && errors.route">{{ errors.route[0] }}</span>               
                </div>
                
              </div> 
              <div class="row">                               
                <div class="form-group col-md-6">
                  <label>Descripción</label>
                  <span class="mdi mdi-alert-circle circle-info" title="Ingrese la descripción del producto"></span>
                  <textarea  class="form-control form-control-sm" v-model="menu.description" ></textarea>
                  <span class="errors" v-if="errors && errors.description">{{ errors.description[0] }}</span>               
                </div>
              </div> 

              <div class="row">                               
                <div class="form-group col-md-12">
                  <label>Seleccione un icono</label>
                  <span class="errors" v-if="errors && errors.description">{{ errors.description[0] }}</span>               
                  <br>

                
                  <span v-for="(data_icons, key_icon) of icons"  :key="key_icon">
                      <div class="title-section-icons" data-toggle="collapse" :href='"#icons-"+ data_icons.title +""'>{{data_icons.title}}</div>

                      <div :id='"icons-"+ data_icons.title +""' class="collapse">

                          <span v-for="(icon, key_di) of data_icons.icons" :key="key_di">
                            <i v-if="icon.class == menu.path_icon" :class="icon.class" class="icon-selected" @click="selectIcon(icon)"></i>

                            <i v-else :class="icon.class" class="icon-to-select" @click="selectIcon(icon)"></i>
                          </span>

                      </div>
                      
                  </span>
               
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
          menu : {
            id        : this.$route.params.id,
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            name       : "" ,
            route      : "",
            description: "",
            parent_id  : "",
            path_icon  : "",         
            post_event : "update"  
          },
          list_menus: [],
          icons: []
        }
      },       
    created() {       
      this.initData()     
    },    
    methods: {
      async initData () {
        await this.getData()
        await this.getMenus()
        await this.getIcons()       
        this.loader = false
      },
      selectIcon (icon) {
        this.menu.path_icon = icon.class
      },    
      getMenus () {
        var self = this
        this.$http.get('/menus-parent')
        .then(response => {    
          self.list_menus = response.body   
        }, response => {
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      },  
      getIcons () {
        var self = this
        this.$http.get('../../faw/customicons.json')
        .then(response => {   

          self.icons = response.body.data
          console.log(self.icons)
        }, response => {                 
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      }, 
      getData () {
        var self = this
        this.$http.get('/get-menu', {params: {id: self.menu.id}})
        .then(response => {       
          self.menu.name = response.body.name 
          self.menu.description = response.body.description 
          self.menu.path_icon = response.body.path_icon 
          self.menu.route = response.body.route 
          self.menu.parent_id = response.body.parent_id    
        }, response => {                 
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      },            
      postUpdate () {
        event.preventDefault()       
        this.loader = true         
        var self = this  
        this.$http.post('/menu-store', this.menu)
        .then(response => {
          self.$toasted.global.APP_GENERAL_SUCCESS()
          self.errors = {}
          self.$router.push({ path: "/menus"})
        }, response => {             
          self.loader = false
    
          if (response.status === 422) {
            self.$toasted.global.APP_GENERAL_ERROR_FORM()
            self.errors = response.body.errors
          }
        })
      },
      isNumber: function(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
          evt.preventDefault();;
        } else {
          return true;
        }
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
