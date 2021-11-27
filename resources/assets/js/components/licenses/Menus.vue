<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
          <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/licenses">               
          <p class="text-muted mb-0 hover-cursor">&nbsp;licencias&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">menus</p>             
      </div> 

      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Configuración de los menus de la licencia [ {{ license.name }} ]</h2>         
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para actualizar la información" @click="postStore">
            <router-link to="/licenses">
              <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
            </router-link> 
          </div>
        </div> 
      </div>    
        <hr>      
      <div v-for="(menu, key_menu) of menus" :key="key_menu">
        <div class="div-parent-menu">
          {{ menu.name }}  <span class="span-description-menu">{{menu.description}}</span> 
          <input class="checkbox-right" type="checkbox" v-model="list_to_select[menu.id].checked" @change="changeCheckedMenu(menu)">
        </div>
        
        <div v-for="(submenu, key_submenu) of menu.sub_menus" :key="key_submenu">
          <div class="div-submenu-menu">
            {{ submenu.name }} 
            [<span class="span-description-menu">{{submenu.description}}</span>] 
            <input class="checkbox-right" type="checkbox" v-model="list_to_select[menu.id].submenus[submenu.id].checked" @change="changeCheckedSubMenu(menu, submenu)">              
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
          errors    : {},             
          license : {
            id             : this.$route.params.id,
            _token         : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            name           : "",            
            selected_menus : []
          },
          menus     : [],
          list_to_select : []
        }
      },       
      created() {       
        this.initData()
      },
    methods: {
      async initData () {
        await this.getData()    
        await this.getMenus()
        this.loader = false
      },  
      changeCheckedMenu (menu) {
        var menu_in_list = this.list_to_select[menu.id]
        Object.keys(menu_in_list.submenus).forEach(function (val, index) {
            menu_in_list.submenus[val].checked = menu_in_list.checked ? true : false
        })
      },
      changeCheckedSubMenu (menu, submenu) {
        var menu_in_list = this.list_to_select[menu.id]
        var sub_menu_in_list = this.list_to_select[menu.id].submenus[submenu.id]
        if (sub_menu_in_list.checked) {
          menu_in_list.checked = true
        } else {
          var not_check_menu = true
          Object.keys(menu_in_list.submenus).forEach(function (val, index) {
            if (menu_in_list.submenus[val].checked) {
              not_check_menu = false
            } 
          })

          if (not_check_menu) {
            menu_in_list.checked = false
          }
        }
      },
      getData () {
        var self = this
        this.$http.get('/get-license', {params: {id: self.license.id}})
        .then(response => {       
          self.license.name = response.body.label    
        }, response => {                 
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      },   
      getMenus () {
        var self = this
        this.$http.get('/get-menus-format-box', {params: {id: self.license.id}})
        .then(response => { 
            self.menus          = response.body.menus
            self.list_to_select = response.body.list_to_select   
            console.log(self.list_to_select )                    
        }, response => {                 
            self.$toasted.global.APP_GENERAL_ERROR()
        })
      }, 
      postStore () {
        event.preventDefault()  
        this.license.selected_menus = this.list_to_select    
        var self = this  
        this.loader = true
        this.$http.post('/license-menus-store', this.license)
        .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
            self.$router.push({ path: "/licenses"})
            self.loader = false
          }, response => {          
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
<style>
  .div-parent-menu{
    color: #fff;
    background: rgb(29,51,68);
    background: linear-gradient(90deg, rgba(29,51,68,1) 0%, rgba(49,79,86,1) 47%, rgba(61,95,96,1) 97%, rgba(61,95,96,1) 98%, rgba(87,131,119,1) 99%, rgba(170,246,193,1) 100%);
    margin-bottom: 5px;
    padding: 5px 25px;
    font-size: 14px;

  }

  .div-parent-menu:hover{
    transition:all .3s ease-in-out;
    -webkit-transform:scale(1.01);
    transform:scale(1.01);
  }

  .div-submenu-menu{
    color: #3ad38b;
    background-color:#fafafa;
    margin-bottom: 5px;
    padding: 5px 35px 5px 50px;
    font-size: 12px;
  }

  .div-submenu-menu:hover{
    transition:all .3s ease-in-out;
    -webkit-transform:scale(1.01);
    transform:scale(1.01);
  }

  .span-description-menu {
    color: #3ad38b;
    font-size: 9px;
  }

  .checkbox-right {
    float: right;
  }
</style>
@endsection
