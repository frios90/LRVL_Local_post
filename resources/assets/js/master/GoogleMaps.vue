<template>
  <gmap-map
    :center="{lat: markers[0].position.lat, lng: markers[0].position.lng}"
    :zoom="15"
    style="width: 100%; height: 350px"
  >
    <gmap-marker
      :key="index"
      v-for="(m, index) in markers"
      :position="m.position"
      :title="m.title"
      :clickable="true"
      :draggable="true"
      @click="center=m.position"
      @drag="updateCoordinates(m.position)"
    ></gmap-marker>
  </gmap-map>
</template>
<script>
  export default {
    data () {
      return {  
        new_coordinates : {}     
      }
    },
    created () {
      console.log(this.markers)
      console.log(this.markers[0].position.lat)
      console.log(this.markers[0].position.lng)
    },
    props: {        
        title: String,
        markers: Array
    },
    methods: {
      updateCoordinates(position) {
        // this.coordinates = {
        //   lat: location.latLng.lat(),
        //   lng: location.latLng.lng(),
        // }
        console.log(position)
      }
    }
  }
</script>