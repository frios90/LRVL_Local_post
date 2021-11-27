import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        request: ''
    },
    mutations: {
        setRequest(state, resultRequest) {
            state.request = resultRequest            
        }
    },
    actions: {   
        async getRequest({commit}, request_id) {           
            try {            
                let data = await axios.get('/get-request', {
                    params: {
                        id: request_id
                    }
                })                
                commit('setRequest', data.data.request)      
            } catch (error) {
            } finally {
            }       
        }
    },
    modules: {
    }
})
