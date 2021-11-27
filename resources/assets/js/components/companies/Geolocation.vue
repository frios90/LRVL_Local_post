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
        <p class="text-muted mb-0 hover-cursor">geolicalización</p>             
      </div>  
      <div class="row div-title-module">
        <div class="col-md-10">
          <h2>Geolocalización del compañi [ {{ company.name }} ]</h2>         
        </div>
        <div class="col-md-2">
          <div class="btn-main-view-action">
            <img src="/intranet/img/icons/comprobar.png" class="icon-action-module float-right"  title="Presione para guardar el dirección" @click="postStore()">
            <router-link to="/companies">
              <img src="/intranet/img/icons/volver.png" class="icon-action-module float-right"  title="Presione para ir al menu anterior.">
            </router-link> 
          </div>
        </div> 
      </div> 

      <hr>   
      <div class="card">          
        <div class="card-body">  
          <div class="row">
            <div class="col-md-6">
              <gmap-map
                :center="center_map_in"
                :zoom="11"
                style="width: 100%; height: 350px"
              >
                <gmap-marker
                  :key="index"
                  v-for="(m, index) in markers"
                  :position="m.position"
                  :title="m.title"
                  :clickable="m.click"
                  :draggable="m.drag"
                  :icon="m.marker_option"
                  @click="center=m.position"
                  @drag="updateCoordinates"
                ></gmap-marker>
              </gmap-map>
            
            
            </div> 


            <div class="row-md-6">
              <form class="form-basic"> 
                    <div class="form-group col-md-12">
                      <label for="">Región</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Seleccione la región del marcador"></span>
                      <select class="select-css" v-model="address.region_id" name="region_id" @change="getCommunes()">
                        <option value="0" selected>Seleccione una región</option>
                        <option  v-for="(list, index) in region_list" :key="index" :value="list.id">
                          {{ list.name }}
                        </option>
                      </select>
                      <span class="errors" v-if="errors && errors.region_id">{{errors.region_id[0] }}</span>               
                    </div>
                    <div class="form-group col-md-12">
                      <label for="">Comuna</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Seleccione la comuna del marcador"></span>
                      <select class="select-css" v-model="address.commune_id">
                        <option value="0" selected>Seleccione una comuna</option>
                        <option v-for="(list, index) in commune_list" :key="index" :value="list.id" name="commune_id">                            
                          {{ list.name }}
                        </option>
                      </select>
                      <span class="errors" v-if="errors && errors.commune_id">{{errors.commune_id[0] }}</span>               
                    </div>
               
                  <div class="form-group col-md-12">
                    <label for="">Dirección</label>
                    <span class="mdi mdi-alert-circle circle-info" title="Ingrese la dirección del marcador"></span>
                    <input type="text" class="form-control form-control-sm" v-model="address.detail" name="address">
                    <span class="errors" v-if="errors && errors.address">{{errors.address[0] }}</span>               
                  </div>
                  <div class="row col-md-12">
                    <div class="form-group col-md-6">
                      <label for="">Latitud</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Ingrese la dirección del marcador"></span>
                      <input type="text" class="form-control form-control-sm" v-model="current_location.lat" name="lat" readonly>
                      <span class="errors" v-if="errors && errors.lat">{{errors.lat[0] }}</span>               
                    </div>
                    <div class="form-group col-md-6">
                      <label for="">Longitud</label>
                      <span class="mdi mdi-alert-circle circle-info" title="Ingrese la dirección del marcador"></span>
                      <input type="text" class="form-control form-control-sm" v-model="current_location.lng" name="lng" readonly>
                      <span class="errors" v-if="errors && errors.lng">{{errors.lng[0] }}</span>               
                    </div>
                  </div>
                  
              </form>
            </div> 
          </div>    





          <div class="">
            <table class="table">
              <thead>
                <tr>
                  <th>Región</th>
                  <th>Comuna</th>
                  <th>Dirección</th>
                  <th>Lal/Lon</th>
                  <th>Acciones</th>
                </tr>
              </thead>  
              <tbody>
                <tr v-for="(location, key_location) of company.locations" :key="key_location">
                  <td>{{location.region.name}}</td>
                  <td>{{location.commune.name}}</td>
                  <td>{{location.address}}</td>
                  <td>{{location.lat}} / {{location.lng}}</td>
                  <td>Acciones</td>
                </tr>
                
                
              </tbody>  
            </table>  
          </div>      
                 
        </div>
      </div>   
    </div>   
</template>
<
<script type="text/javascript" src="https://google-maps-utility-library-v3.googlecode.com/svn-history/r391/trunk/markerwithlabel/src/markerwithlabel.js"></script>
<script>
  import Util from '../mixins/Util.js'
  import GoogleMap from "../../master/GoogleMaps";
  export default {
    components: {
      GoogleMap
    },
    mixins: [Util],
      data () {
        return {
          errors    : {}, 
          map: '',       
          region_list  : [],
          commune_list : [],    
          company : {
            id        : this.$route.params.id,
            _token     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),            
            name       : "",
            locations  : []
          },
          address: {
            region_id  : "",
            commune_id : "",
            detail     : "" 
          },
          current_location: {},
          center_map_in : {
            lat: 0,
            lng: 0
          },
          markers: []
          
        }
      },       
      created() {       
        this.getData()
        this.geolocation()
        this.getRegions()
      },
      mounted () {
        var self = this
        setTimeout(function(){ self.initMarker() }, 1000);        
      },
     
    methods: {  

      geolocation : function() {
        var self = this
        navigator.geolocation.getCurrentPosition((position) => {
          self.current_location = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          }         
        }) 

      },
      initMarker () { 
        var self = this
        this.center_map_in = {
          lat: this.current_location.lat,
          lng: this.current_location.lng
        }
        console.log(google.maps.SymbolPath.CIRCLE)
        var marker = {
          position: {
              lat: this.current_location.lat,
              lng: this.current_location.lng
            },
            title: 'Busca las coordenadas para configurar un nuevo marcador.',
            click: true,
            drag: true,
            marker_option: {
              url: '../../intranet/img/markers/iconmarker4.png',
              size: {width: 30, height: 30, f: 'px', b: 'px',},
              scaledSize: {width: 30, height: 30, f: 'px', b: 'px',},
              
            }
          }
        this.markers.push(marker)     
        if (this.company.locations) {
            this.company.locations.forEach(function (val, index) {

            var marker = {
              position: {
                lat: parseFloat(val.lat),
                lng: parseFloat(val.lng)
              },
              title: val.region.name +", "+val.commune.name+", "+val.address,
              click: false,
              drag: false,
              marker_option: {
              url: '../../intranet/img/markers/iconmarker3.png',
              
              size: {width: 30, height: 30, f: 'px', b: 'px',},
              scaledSize: {width: 30, height: 30, f: 'px', b: 'px',},
            }
            }

            self.markers.push(marker)
          })
          
        }       
      },
      getData () {
        var self = this
        this.$http.get('/get-company', {params: {id: self.company.id}})
        .then(response => {                    
          self.company.name       = response.body.name
          self.company.locations  = response.body.company_geolocation 
    
        }, response => {                 
          self.$toasted.global.APP_GENERAL_ERROR()
        })
      },  
      getRegions ()  {
        var self = this  
        this.$http.get('/region-list').then(function(response){
            self.region_list = response.body
            }, function(){
                  this.$toasted.global.APP_GENERAR_ERROR()
            })           
      },
      getCommunes ()  {
          this.commune_id = ''
          var self = this  
            this.$http.get('/commune-list', {params: {region: self.address.region_id}}).then(function(response){
                self.commune_list = response.body
                }, function(){
                      this.$toasted.global.APP_GENERAR_ERROR()
                })           
      },
      changeUbication (lat, lng) {
        this.markers[0].position.lat = lat
        this.markers[0].position.lng = lng
      },
      updateCoordinates(location) {
        this.current_location.lat = location.latLng.lat()
        this.current_location.lng = location.latLng.lng()       
      },
      postStore () {
        event.preventDefault()      
        var self = this  
        var send = {
          company_id    :this.company.id,
          _token     : this.company._token,
          lat        : this.current_location.lat,
          lng        : this.current_location.lng,
          region_id  : this.address.region_id,
          commune_id : this.address.commune_id,
          address    : this.address.detail

        }
        this.$http.post('/company-geolocation-store', send)
        .then(response => {
            self.$toasted.global.APP_GENERAL_SUCCESS()
            self.errors = {}
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
    
  }
</script>

@endsection
