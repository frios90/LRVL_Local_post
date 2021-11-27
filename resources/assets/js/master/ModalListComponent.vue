<template>
  <modal name="list" @before-open="beforeOpen">  
      <div class="modal-body">
        <div class="modal-content">
            <table v-if="list == 'projects'" class="">
              <thead class="">
                <tr>
                  <th class="">Folio</th>
                  <th class="">Descripción</th>
                </tr>
              </thead>
              <tbody>
                <tr  v-for="(row, index) in data" :key="index">
                  <td class="">{{ row.folio }}</td>
                  <td class="">{{ row.description }}</td>
                </tr>
              </tbody>   
            </table>
            <table v-if="list == 'description'" class="">
              <thead class="">
                <tr>
                  <th class="">Descripción</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="">{{ data }}</td>
                </tr>
              </tbody>   
            </table>
            <table v-if="list == 'staff'" class="">
              <thead class="">
               <tr>
                  <th class="">Colaboradores</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, index) in data" :key="index">
                  <td class="">{{ row.name }}</td>
                </tr>
              </tbody>   
            </table>
        </div>
      </div>
  </modal> 
</template>

<script>
export default {
  data () {
            return {
              list: "",
              data: ""
            }
        }, 
  created() {
  }, 
  methods: { 
    beforeOpen (event) {
      this.list=event.params.list
      this.data=event.params.dts
    }
  }
}
</script>

<style>
.modal-head {
  padding: 10px;
  background-color: #f5fbf1!important;
  color:#373a6d;
}
.modal-head .title {
  margin-left: 20px;
}
.modal-content{
  text-align: center;
}
</style>