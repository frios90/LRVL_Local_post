<template >     
      <div class="content-wrapper">
        <div v-if="loader" class="loader"></div>
        
        <div class="d-flex div-navegate-page">    
          <router-link to="/dashboard">               
            <p class="">inicio&nbsp;/</p>             
          </router-link>
          <p class="hover-cursor">&nbsp;reportes</p>          
        </div>
        
        <div class="row div-title-module">
          <div class="col-md-10">
            <h2>Reporte de productos con stock critico</h2>          
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
            <span slot="inputqty" slot-scope="props">
                <input type="text" class="input-change-price-table"  style="text-align: center; width: 70px!important" v-model="props.row.qty" @keyup="changeQty(props.row, '/mono-company-products-change-qty', '/mono-company-products-table-list')" maxlength="7" @keypress="isNumber($event)">
            </span>
            <span slot="inputprice" slot-scope="props">
                $<input type="text" class="input-change-price-table"  style="text-align: center; width: 70px!important" v-model="props.row.price" @keyup="changePrice(props.row, '/mono-company-products-change-price', '/mono-company-products-table-list')" maxlength="7" @keypress="isNumber($event)">
            </span>
            <span slot="critical" slot-scope="props">
                <input type="text" class="input-change-price-table"  style="text-align: center; width: 70px!important" v-model="props.row.critical_qty" @keyup="changeCriticalQty(props.row, '/mono-company-products-change-critical-qty', '/mono-company-products-table-list')" maxlength="7" @keypress="isNumber($event)">
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
        this.getDataTable('/mono-company-products-critical-stock-table-list')
        this.columns          =  ['product.barcode', 'product.name', 'inputqty', 'critical']
        this.options.headings = {                
            'product.barcode' : 'Código de Barras',
            'product.name'    : 'Nombre',
            inputqty          : 'Stock Activo',
            critical          : 'Stock Critico',
        }
        this.options.sortable = [
            'products.barcode', 'products.name'           
        ]
      },
      methods: {
        changeStatus(id, url, table) {
          this.loader = false
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

