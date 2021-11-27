<template >     
  <div class="content-wrapper">
    <div v-if="loader" class="loader"></div>
    <div class="d-flex div-navegate-page">
      <p class="text-muted mb-0 hover-cursor">&nbsp;inicio</p>             
    </div>  

    <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Bienvenido/a </h2>          
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            




          </div>
        </div> 
    </div>        
   <hr>
   <div>
     <div class="row row-widgets">
       <div class="col-md-3"> 
          <div class="widget grid-widget-a widget-tickets">
              <div class="grid-widget-a-div1"><i class="fas fa-donate"/> Ventas <hr></div>
              <div class="grid-widget-a-div2">
                <div class="div-main-value">{{main_widgets.sales}}</div>
                <div class="span-main-value">hoy</div>
              </div>
              
            
          </div>
       </div>
       <div class="col-md-3"> 
         <div class="widget grid-widget-a widget-quotations">
              <div class="grid-widget-a-div1"><i class="fas fa-hand-holding-usd"/> Cotizaciones <hr></div>
              <div class="grid-widget-a-div2">
                <div class="div-main-value">{{main_widgets.quotations}}</div>
                <div class="span-main-value">hoy</div>
              </div>             
            
          </div>
       </div>

      <div class="col-md-3"> 
         <div class="widget grid-widget-a widget-customers">
              <div class="grid-widget-a-div1"><i class="fas fa-users"/> Clientes <hr></div>
              <div class="grid-widget-a-div2">
                <div class="div-main-value">{{main_widgets.customers}}</div>               
              </div>
          </div>
       </div>

       <div class="col-md-3"> 
         <div class="widget grid-widget-a widget-providers">
              <div class="grid-widget-a-div1"><i class="fas fa-people-carry"/> Proveedores  <hr></div>
              <div class="grid-widget-a-div2">
                <div class="div-main-value">{{main_widgets.providers}}</div>               
              </div>
          </div>
       </div>
     </div>
   </div>
   
      
  </div>
</template>
<script>

  import Util from '../mixins/Util.js'
  import chartTicketWidget from '../CHARTS/chartTicketWidget.vue'
    export default {
       mixins: [Util],      
        name: 'home-general',        
        component: {chartTicketWidget},
        data () {
            return {
                loader: "",
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                main_widgets: { sales: 0, quotations: 0, customers: 0, providers: 0 }
            }

        },
        created () {
          var self = this
          self.mainWidgets()
          setInterval(function(){ 
            self.mainWidgets()
          }, 5000);
          
        },        
        methods: {
          async mainWidgets() {
            var self = this
            await this.$http.get('/get-main-widgets').then(function(response) {
                self.main_widgets = response.body
            }, function() {
            })
          },
        },
        
    }
</script>
<style>
.row-widgets {
  height: 89px;
}
  .widget {
    background-color: royalblue;
    height: 100%;
    width: 100%;
    padding: 10px 25px 10px 15px;
    color: #fff;

  }  
  
  .grid-widget-a {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: 1fr;
    grid-column-gap: 0px;
    grid-row-gap: 0px; 

    -webkit-box-shadow: 3px 3px 5px 0px rgba(217,236,255,1);
    -moz-box-shadow: 3px 3px 5px 0px rgba(217,236,255,1);
    box-shadow: 3px 3px 5px 0px rgba(217,236,255,1);
    filter: saturate(1.3); 
    

  }

  .grid-widget-a:hover {
    transition: .3s;
    cursor:pointer;
    filter: saturate(1.8); 
  }
  .widget-tickets {
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(96, 162, 248) 0%, rgb(212, 170, 246) 100%);
    border-radius: 5px 5px 5px 5px;
    -moz-border-radius: 5px 5px 5px 5px;
    -webkit-border-radius: 5px 5px 5px 5px;
  }

    .widget-quotations {
    
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(163, 134, 221) 0%, rgb(134, 253, 237) 100%);
    border-radius: 5px 5px 5px 5px;
    -moz-border-radius: 5px 5px 5px 5px;
    -webkit-border-radius: 5px 5px 5px 5px;
  }

    .widget-customers {
    
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(166, 218, 253) 0%, rgb(138, 250, 201) 100%);
    border-radius: 5px 5px 5px 5px;
    -moz-border-radius: 5px 5px 5px 5px;
    -webkit-border-radius: 5px 5px 5px 5px;
  }

    .widget-providers {
    
    background: rgb(29, 51, 68);
    background: linear-gradient(90deg, rgb(134, 221, 214) 0%, rgb(134, 172, 253) 100%);
    border-radius: 5px 5px 5px 5px;
    -moz-border-radius: 5px 5px 5px 5px;
    -webkit-border-radius: 5px 5px 5px 5px;
  }

  .grid-widget-a-div1 { 
    grid-area: 1 / 1 / 2 / 3;
    padding: 10px 5px;  
    font-size: 12px;
    letter-spacing: 1px;
    font-weight: 600;
  }
  .grid-widget-a-div2 { grid-area: 1 / 3 / 2 / 4; }

  
  .div-main-value {
    font-size: 35px;
    text-align: right;
  }
  .span-main-value {
    font-size: 16px;
    font-style: italic;
    text-align: right!important;
  }

   
</style>
@endsection

