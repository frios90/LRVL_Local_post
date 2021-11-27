<template >     
      <div class="wrapper-initial-map">
         <gmap-map :center="center_map_in" :zoom="12" class="map-init-home">
              </gmap-map> 
      </div>          
</template>
<script>
  import Util from '../mixins/Util.js'
  import GoogleMap from "../../master/GoogleMaps"
    export default {
      components: {
      GoogleMap
    },
        name: 'home-general',
        data () {
            return {
                loader: "",
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                title: "Bienvenido",
                sub_title: "Sistema de control y gestión de gastos SOFAG", 
                          current_location: {},
               
            center_map_in : {
            lat: 0,
            lng: 0
          },
                }

        },
        created () {
           this.geolocation()
        },
         mounted () {
        var self = this
        setTimeout(function(){ self.initMarker() }, 2000);        
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

        this.center_map_in = {
          lat: this.current_location.lat,
          lng: this.current_location.lng
        }

      },

         initMarker () { 
            var self = this
            this.center_map_in = {
              lat: this.current_location.lat,
              lng: this.current_location.lng
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
  .map-init-home {
    width: 100%; height: 638px!important;
  }


  @media (max-height: 798px) {
    .map-init-home {
      width: 100%; height: 797px;
    }
  }
  .wrapper-initial-map {
    padding: 0px!important;
  }
</style>
@endsection

