<template >
   <div class="content-wrapper">
      <div v-if="loader" class="loader"></div>
      <div class="d-flex div-navegate-page">    
        <router-link to="/dashboard">               
          <p class="">inicio&nbsp;/</p>             
        </router-link>
        <router-link to="/categories">               
          <p class="text-muted mb-0 hover-cursor">&nbsp;categorías&nbsp;/&nbsp;</p>             
        </router-link>
        <p class="text-muted mb-0 hover-cursor">subcategorías</p>             
      </div> 

      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Grupo de subcategorías de [ {{ category.name }} ]</h2>         
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            <router-link to="/categories">
              <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
            </router-link> 
          </div>
        </div> 
      </div>    
      <hr>        
      <form class="form-basic">       
          <div class="row">                
            <div class="form-group col-md-6">
              <label>Crear una nueva subcategoría</label>
              <span class="mdi mdi-alert-circle circle-info" title="Ingrese un nombre para la subcategoría"></span>
              <input type="text" class="form-control form-control-sm" v-model="subcategory.label" name="label" maxlength="35" placeholder="Ingrese nombre de la subcategoría" >
              <span class="errors" v-if="errors && errors.label">{{ errors.label[0] }}</span>               
            </div>
              <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para actualizar la información" @click="postStore">
             
          </div>      
      </form> 
      <hr>             
      <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
        <span slot="label" slot-scope="props">
          <input type="text" class="input-edit-category" v-model="props.row.label">
        </span>
        <span slot="status" slot-scope="props">
          <span v-if="!props.row.deleted_at" class="general-status-row-active">Activo</span>
          <span v-else class="general-status-row-inactive">Inactivo</span>
        </span>
        <span slot="actions" slot-scope="props">
          <img v-if ="!props.row.deleted_at" src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="Presione para editar el registro" @click="postUpdate(props.row)">                  
          <span v-if="!props.row.has_products">
            <img v-if ="!props.row.deleted_at" src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="presione para desactivar el registro" @click="changeStatus(props.row.id, '/category-status')" >
            <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para activar el registro"  @click="changeStatus(props.row.id, '/category-status')">
          </span>
          <span v-else>
            <img src="/intranet/img/icons/conexiones.png" class="hover-cursor icons-action-row-form" title="Existen productos asociados a este estado por lo que no se puede desactivar.">
          </span>
        </span>                                                     
      </v-client-table>
       
    </div>   
</template>
<script>
  import Table from '../mixins/Table.js'
  import Util from '../mixins/Util.js'
  export default {
    mixins: [Util, Table],
      data () {
        return {
          errors    : {},             
          category : {
            id             : this.$route.params.id,
            _token         : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            name           : "",            
            selected_menus : []
          },
          subcategory : {
            _token      : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            label        : "" ,
            post_event  : 'store',
            category_id : this.$route.params.id,         
          },
          menus     : [],
          list_to_select : []
        }
      },       
      created() {       
        this.getData()
        this.getCategory()
      },
      methods: {    
        getCategory () {
          var self = this
          this.$http.get('/get-category', {params: {id: self.category.id}})
          .then(response => {       
            this.category.name = response.body.label
          }, response => {
            self.$toasted.global.APP_GENERAL_ERROR()
          })
        }, 
        async getData () {
          var self = this
          this.loader = true
          await this.$http.get('/subcategories-table-list', {params: {id: self.category.id}})
          .then(response => {       
            self.loader = false
            self.getDirectDataTable(response.body)
            self.columns          =  [ 'label', 'created_at', 'updated_at', 'deleted_at', 'status', 'actions']
            self.options.headings = {   
              label  : 'Nombre',
              created_at : 'Fecha de ingreso',
              updated_at : 'última actualización',
              deleted_at : 'Fecha de Baja', 
              actions    : 'Acciones',
              status : 'Estado'
            }
            self.options.sortable = [
                'label'             
            ]           
          }, response => {
            self.loader = false
            self.$toasted.global.APP_GENERAL_ERROR()
          })
        },
        async postStore () {
          event.preventDefault()  
          var self = this  
          this.loader = true
          console.log(this.subcategory)
          await this.$http.post('/category-subcategories-store', this.subcategory)
          .then(response => {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.errors = {}
              self.getData()
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
          },
        postUpdate (subcategory) {
          event.preventDefault() 
          this.loader = true     
          var self = this  
          var send_subcategory = {
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            label      : subcategory.label,
            id         : subcategory.id,
            post_event : 'update' 
          }
          console.log(send_subcategory)
          this.$http.post('/category-subcategories-store', send_subcategory)
          .then(response => {
              self.$toasted.global.APP_GENERAL_SUCCESS()
              self.loader = false
              self.getData()
            }, response => {
                self.getData() 
                self.loader = false                
                if (response.status === 422) {
                  self.$toasted.global.APP_GENERAL_ERROR_FORM()
                }else{
                  self.$toasted.global.APP_GENERAL_ERROR()
                }
            })
        },
        changeStatus(id, url) {
            this.loader = true
            var data = {
              _token : this.csrf,
              id     : id
            }
            var self = this
            this.$http.post(url, data)
              .then(response => {
                  self.$toasted.global.APP_GENERAL_SUCCESS()
                  self.loader = false
                  self.getData()
                }, response => {           
                  self.loader = false      
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
