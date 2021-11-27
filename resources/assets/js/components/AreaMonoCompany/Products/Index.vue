<template >     
      <div class="content-wrapper">
        <div v-if="loader" class="loader"></div>
        
        <div class="d-flex div-navegate-page">    
          <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
          </router-link>
          <p class="hover-cursor">&nbsp;productos</p>          
        </div>
        
        <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Configuración de Productos</h2>          
          </div>
          <div class="col-md-2">
            <div class="btn-main-view-action">
              <router-link :to="{ name: 'mono-company-products.create' }">
                <img src="/intranet/img/icons/anadir.png" class="icon-action-module float-right" title="Presione para ir a la creación de un nuevo registro.">
              </router-link> 
            </div>
          </div> 
        </div>                               
        <hr>
       
        <v-client-table  class="index-table" :data="tableData" :columns="columns" :options="options">
           <span slot="status" slot-scope="props">
              <span v-if="!props.row.deleted_at" class="general-status-row-active">Activo</span>
              <span v-else class="general-status-row-inactive">Inactivo</span>
            </span>
          <span slot="inputqty" slot-scope="props">
            <input v-if="!props.row.deleted_at" type="text" class="input-change-price-table"  style="text-align: center; width: 70px!important" v-model="props.row.qty" @keyup="changeQty(props.row, '/mono-company-products-change-qty', '/mono-company-products-table-list')" maxlength="7" @keypress="isNumber($event)">
            <span v-else> {{props.row.qty }}</span>
          </span>
          <span slot="inputprice" slot-scope="props">
            <input v-if="!props.row.deleted_at" type="text" class="input-change-price-table"  style="text-align: center; width: 70px!important" v-model="props.row.price" @keyup="changePrice(props.row, '/mono-company-products-change-price', '/mono-company-products-table-list')" maxlength="7" @keypress="isNumber($event)">
            <span v-else> {{props.row.price }}</span>
          </span>
          <span slot="critical" slot-scope="props">
            <input v-if="!props.row.deleted_at" type="text" class="input-change-price-table"  style="text-align: center; width: 70px!important" v-model="props.row.critical_qty" @keyup="changeCriticalQty(props.row, '/mono-company-products-change-critical-qty', '/mono-company-products-table-list')" maxlength="7" @keypress="isNumber($event)">
            <span v-else> {{props.row.critical_qty }}</span>
          </span>
          <span slot="dates" slot-scope="props">
            <table style="width: 100%;">
              <tbody>
                <tr>
                  <td>Creada</td><td>{{props.row.created_at}}</td>
                </tr>
                <tr v-if="props.row.created_at != props.row.updated_at && props.row.deleted_at != props.row.updated_at">
                  <td>Actualizada: </td> <td> {{props.row.updated_at}}</td>
                </tr>
                <tr v-if="props.row.deleted_at">
                  <td>Eliminada: </td> <td> {{props.row.deleted_at}}</td>
                </tr>
              </tbody>
            </table>        
          </span>
          <span slot="actions" slot-scope="props">
            <router-link v-if ="!props.row.deleted_at" :to="'/mono-company-products/edit/'+props.row.id">
              <img src="/intranet/img/icons/lapiz.png" class="hover-cursor icons-action-row-form" title="ir a la edición del producto">
            </router-link> 
            <router-link v-if ="!props.row.deleted_at" :to="'/mono-company-products/cost/'+props.row.id">
              <img src="/intranet/img/icons/inversion.png" class="hover-cursor icons-action-row-form" title="ir a la configuración de costo por proveedores">
            </router-link> 
            <router-link v-if ="!props.row.deleted_at" :to="'/mono-company-products/see/'+props.row.id">
              <img src="/intranet/img/icons/buscar.png" class="hover-cursor icons-action-row-form" title="Ver el detalle del producto">
            </router-link>                   
            <router-link v-if ="!props.row.deleted_at" :to="'/mono-company-products/upload-photos/'+props.row.product.id">
              <img src="/intranet/img/icons/camara-fotografica.png" class="hover-cursor icons-action-row-form" title="Presione para ir a la galería de imagenes del producto">
            </router-link>                   
            <img v-if ="!props.row.deleted_at"  src="/intranet/img/icons/on.png" class="hover-cursor icons-action-row-form" title="presione para desactivar el registro" @click="changeStatus(props.row.id, '/mono-company-products-status', '/mono-company-products-table-list')" >
            <img v-else src="/intranet/img/icons/off.png" class="hover-cursor icons-action-row-form" title="presione para activar el registro"  @click="changeStatus(props.row.id, '/mono-company-products-status', '/mono-company-products-table-list')">
          </span>                                                     
        </v-client-table>
          
      </div>          
</template>
<script>
    import Table from '../../mixins/Table.js'
    import Util  from '../../mixins/Util.js'
    export default {
      mixins: [Table, Util],
      data () {
        return {
          loader : true,
        }
      },
      created () {
        this.getDataTable('/mono-company-products-table-list')
        this.columns          =  ['product.barcode', 'product.name', 'inputqty', 'inputprice', 'critical', 'dates', 'status','actions']
        this.options.headings = {                
            'product.barcode' : 'Código de Barras',
            'product.name'    : 'Nombre',
            inputqty          : 'Stock Activo',
            inputprice        : 'Precio Base $',
            critical          : 'Stock Critico',
            dates             : 'Fechas',  
            status             : 'Estados',          
            actions           : 'Acciones',
        }
        this.options.sortable = [
            'products.barcode', 'products.name'           
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
                self.$toasted.global.APP_GENERAL_SUCCESS()
                self.loader = false
                self.getDataTable(table)
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
        changePrice(product, url, table) {
          this.loader = false
          var data = {
            _token : this.csrf,
            id     : product.id,
            price  : product.price
          }
          if (data.price) {
              var self = this
              this.$http.post(url, data)
              .then(response => {
                  
                  self.loader = false
                }, response => {                 
                    if (response.status === 422) {
                      self.$toasted.global.APP_GENERAL_ERROR_FORM()
                      self.errors = response.body.errors
                    }else{
                      self.$toasted.global.APP_GENERAL_ERROR()
                    }
                }) 
            }
        },
        changeQty(product, url, table) {
          this.loader = false
          var data = {
            _token : this.csrf,
            id     : product.id,
            qty    : this.validateNumber(product.qty) ? this.validateNumber(product.qty) : 0
          }
          if (data.qty) {
              var self = this
              this.$http.post(url, data)
              .then(response => {
                  
                  self.loader = false
                }, response => {                 
                    if (response.status === 422) {
                      self.$toasted.global.APP_GENERAL_ERROR_FORM()
                      self.errors = response.body.errors
                    }else{
                      self.$toasted.global.APP_GENERAL_ERROR()
                    }
                }) 
            }
        },
        changeCriticalQty(product, url, table) {
          this.loader = false
          var data = {
            _token : this.csrf,
            id     : product.id,
            critical_qty : this.validateNumber(product.critical_qty) ? this.validateNumber(product.critical_qty) : 0
          }
          if (data.critical_qty) {
              var self = this
              this.$http.post(url, data)
              .then(response => {                  
                  self.loader = false
                }, response => {                 
                    if (response.status === 422) {
                      self.$toasted.global.APP_GENERAL_ERROR_FORM()
                      self.errors = response.body.errors
                    }else{
                      self.$toasted.global.APP_GENERAL_ERROR()
                    }
                }) 
            }
          }      
      },
      filters: {
        upper: function (value) {
            return value.toUpperCase();
        }
      },
      computed: {
        rows() {
          return this.items.length
        }
      }
    }
</script>
<style>
  .input-change-price-table {
      padding: 5px!important;
      text-align: center!important;
  }
</style>
@endsection

