
<script>
  import { Line } from 'vue-chartjs'
  export default {
    extends: Line,
    props: ['options'],
    mounted () {
      var self = this
      this.renderChart(response.body.data, response.body.options)
    //   this.$http.get("/get-global-chart").then(
    //     function(response) {
    //         this.renderChart(response.body.data, response.body.options)
    //     },
    //     function() {
    //       alert("Error!");
    //     }
    //   )  
    }
  }
</script>
<style>
</style>