<template>      
  <div>
    <div class="div-main-img-menu-side-bar">
      <i :class="img_menu" class="main-img-menu-side-bar" data-toggle="minimize"></i>
    </div>   
    <div v-if="has_critical_alert && (Auth.profile.code == 'ADMIN' || Auth.profile.code == 'SUDO')" class="div-main-img-alert-critical-stock">
      <router-link to="/report/company-critical-stock">
        <i class="main-img-alert-critical-stock fas fa-exclamation-circle" title="Alerta de productos con stock crítico" :class="altern_alarm_style"></i>  
      </router-link> 
    </div> 
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul  class="nav">
        <li v-for="(menu, key_menu) of menus" :key="key_menu" class="nav-item" data-toggle="collapse" :href='"#parent-menu-"+ menu.id +""'>
          <a class="nav-link" href="#"  @click="selectMenu(menu.id)" :id="'id-a-menu'+menu.id">
            <router-link :to="menu.route">      
              <i :class="menu.path_icon" class="menu-link-icon" @click="changeimgMain(menu.path_icon)" :title="menu.description"></i>
              <span class="menu-title" @click="changeimgMain(menu.path_icon)">{{ menu.name }}</span>
              <i v-if="menu.sub_menus.length > 0" class="fas fa-sort-down in-right"></i>
            </router-link>
          </a>
          <div :id='"parent-menu-"+ menu.id +""' aria-expanded="false" class="collapse div-menu-collapse">
              <div v-for="(submenu, key_submenu) of menu.sub_menus" :key="key_submenu" class="item-submenu">
                <a class="a-submenu" href="#" @click="selectSubMenu(menu.id, submenu.id)" :id="'id-a-menu'+submenu.id">
                  <router-link :to="submenu.route" :id="'id-span-submenu'+submenu.id">      
                    <i :class="submenu.path_icon" class="menu-link-icon" @click="changeimgMain(submenu.path_icon)" :title="submenu.description"></i>
                    <span  class="menu-title submenu-label" @click="changeimgMain(submenu.path_icon)">{{ submenu.name }}</span>
                  </router-link>
                </a>  
              </div>             
          </div>
        </li>
        <li class="nav-item" >
          <a class="nav-link" href="#" @click="logOut">
            <router-link to="">   
              <i class="fas fa-sign-out-alt"></i>   
              <span class="menu-title">Salir</span> 
            </router-link>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
  import Util from '../components/mixins/Util.js'
  export default {
      mixins: [Util],
      data() {
          return {
              csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              img_menu: 'fas fa-tachometer-alt',
              menus: [],
              has_critical_alert: false,
              altern_alarm: true,
              altern_alarm_style: 'true-style-alert-altern'
          }
      },
      created() {
          this.getMenus()
          var self = this

          setInterval(function() {
              self.getAlertCriticalStock()
          }, 2000);

          setInterval(function() {
              if (self.altern_alarm) {
                  self.altern_alarm = false
                  self.altern_alarm_style = 'false-style-alert-altern'
              } else {
                  self.altern_alarm = true
                  self.altern_alarm_style = 'true-style-alert-altern'
              }
          }, 250);
      },
      methods: {
          getMenus() {
              var self = this
              this.$http.get('/menus-for-menu')
                  .then(response => {
                      self.menus = response.body
                  }, response => {
                      self.$toasted.global.APP_GENERAL_ERROR()
                  })
          },
          async getAlertCriticalStock() {
              var self = this

              await this.$http.get('/mono-company-products-critical-stock-table-list')
                  .then(response => {
                      if (response.body.length > 0) {
                          self.has_critical_alert = true
                      } else {
                          self.has_critical_alert = false
                      }
                  }, response => {
                      window.location.replace("/")
                  })
          },
          changeimgMain(img) {
              this.img_menu = img
          },

          logOut() {
              this.$swal({
                  title: '¿Esta seguro/a?',
                  text: 'Esta apunto de salir de la sesión.',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Sí',
                  cancelButtonText: 'No',
                  showCloseButton: true
              }).then((result) => {
                  if (result.value) {
                      var data = {
                          _token: this.csrf
                      }
                      var self = this
                      this.$http.post('/log-out', data).then(function(response) {
                          console.log(response)
                          location.href = window.location
                      }, function() {
                          alert('Error!');
                      })
                  }
              })
          },
          selectMenu(selected_menu_id) {

              var element = document.getElementById('id-a-menu' + selected_menu_id);
              element.classList.add("selected-menu-color");

              var id_menu_selected = 'parent-menu-' + selected_menu_id + ''
              this.menus.forEach(function(val, ind) {
                  var id_menu = 'parent-menu-' + val.id + ''
                  if (id_menu != id_menu_selected) {
                      var element = document.getElementById(id_menu);
                      element.classList.remove("show");

                      element = document.getElementById('id-a-menu' + val.id);
                      element.classList.remove("selected-menu-color");
                  }
              })
          },
          selectSubMenu(menu_id, sub_menu_id) {
              var id_menu_selected = 'parent-menu-' + menu_id + ''
              var element = document.getElementById(id_menu_selected);
              element.classList.remove("show");

              element = document.getElementById('id-span-submenu' + sub_menu_id);
              element.classList.add("selected-submenu-color");

              this.menus.forEach(function(menu, ind) {
                  menu.sub_menus.forEach(function(submenu, ind) {
                      if (submenu.id != sub_menu_id) {
                          element = document.getElementById('id-span-submenu' + submenu.id);
                          element.classList.remove("selected-submenu-color");
                      }
                  })
              })
          }
      }
  }
</script>
<style >
  .item-submenu {
    text-align: left;
    margin-left: 25px;
  }

  .main-img-menu-side-bar {
    font-size: 20px;
    text-align: center;
    color: #fff;
    margin: 25px 15px 15px 15px!important;
    width: 40px;
    border: solid #fff 2px;
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(49, 56, 86), rgb(170, 179, 246) 100%);
    padding: 9px 7px;
    position: fixed;
    z-index: 200!important;
  }

  @media screen and (max-width: 991px) {
    .main-img-menu-side-bar {
      font-size: 15px;
      color: #fff;
      margin: 25px 15px 15px 5px!important;
    }
  }

  .main-img-alert-critical-stock {
    font-size: 25px;
    color: #fff;
    padding: 13px 13px;
    border-radius: 200px 200px 200px 200px;
    -moz-border-radius: 200px 200px 200px 200px;
    -webkit-border-radius: 200px 200px 200px 200px;
    border: solid #fff 2px;
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(225, 27, 27) 0%, rgb(255, 5, 5) 100%);
    -webkit-box-shadow: -8px 9px 6px -7px rgba(51, 51, 51, 1);
    -moz-box-shadow: -8px 9px 6px -7px rgba(51, 51, 51, 1);
    box-shadow: -8px 9px 6px -7px rgba(51, 51, 51, 1);
    position: fixed;
    margin: 40% 15px 15px 100px!important;
    z-index: 200!important;
  }

  .main-img-alert-critical-stock:hover {
    cursor: pointer;
    -webkit-transform: scale(1.3);
    transform: scale(1.3);
    transition: 0.5s;
  }

  .true-style-alert-altern {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
    transition: 0.1;
  }

  .nav-item:hover {
    background-color: #a1d5a7;
    transition: .5s!important;
  }

  .selected-menu-color {
    background-color: #a1d5a7;
    transition: .5s!important;
  }

  .selected-submenu-color {
    color: #c8ffdc!important;
    font-weight: 400;
    font-size: 13px;
  }

  .item-submenu:hover {
    -webkit-transform: scale(1.17);
    transform: scale(1.17);
    transition: .5s!important;
  }

  .main-img-alert-critical-stock {
    font-size: 25px;
    color: #fff;
    padding: 13px 13px;
    border-radius: 200px 200px 200px 200px;
    -moz-border-radius: 200px 200px 200px 200px;
    -webkit-border-radius: 200px 200px 200px 200px;
    border: solid #fff 2px;
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(225, 27, 27) 0%, rgb(255, 5, 5) 100%);
    -webkit-box-shadow: -8px 9px 6px -7px rgba(51, 51, 51, 1);
    -moz-box-shadow: -8px 9px 6px -7px rgba(51, 51, 51, 1);
    box-shadow: -8px 9px 6px -7px rgba(51, 51, 51, 1);
    position: fixed;
    margin: 47% 15px 15px 10px!important;
    z-index: 200!important;
  }

  .main-img-alert-critical-stock:hover {
    cursor: pointer;
    -webkit-transform: scale(1.3);
    transform: scale(1.3);
    transition: 0.5s;
  }

  .true-style-alert-altern {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
    transition: 0.1;
  }
</style>