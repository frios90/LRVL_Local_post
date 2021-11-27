<template >     
      <div class="content-wrapper">
        <div v-if="loader" class="loader"></div>
        
        <div class="d-flex div-navegate-page">    
          <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
          </router-link>
          <p class="hover-cursor">&nbsp;licencias</p>          
        </div>
        
        <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Módulo Gestión de Licencias</h2>          
          </div>          
        </div> 
        <hr>
        <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
          <span slot="slot_description" slot-scope="props">
            <span>{{props.row.description}}</span>
          </span>
          <span slot="actions" slot-scope="props">                 
            <router-link v-if ="!props.row.deleted_at" :to="'/licenses/menus/'+props.row.id">
              <img src="/intranet/img/icons/jerarquia.png" class="hover-cursor icons-action-row-form" title="Presione para ir a la configuración de menus asociados a la licencia">
            </router-link>                 
          </span>
        </v-client-table>
           
      </div>          
</template>
<script>
    import Table from '../mixins/Table.js'
    import Util  from '../mixins/Util.js'
    export default {
      mixins: [Table, Util],
      data () {
        return {
        }
      },
      created () {
        this.getDataTable('/licenses-table-list')
          this.columns          =  ['label', 'slot_description', 'actions']
          this.options.headings = {  
            
            label   : 'Licencia',
            slot_description    : 'Descripción',
            actions    : 'Acciones',
          }
        this.options.sortable = [
            'rut', 'name'             
        ]
      },
      methods: {
        changeStatus(id, url, table) {
          this.loader = true
          var data = {
            _token : this.csrf,
            id     : id
          }
          var self = this
          this.$http.post(url, data)
            .then(response => {
                self.getDataTable(table)
                self.$toasted.global.APP_GENERAL_SUCCESS()
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
      
    }
</script>
<style>
  .td-description-licensia {
    text-align: left!important;
  }
  
</style>
@endsection

