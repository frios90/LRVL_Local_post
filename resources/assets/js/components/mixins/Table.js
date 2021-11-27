export default {
    data () {
        return {
            columns   : [],
            tableData : [],
            options: {
                headings          : {},
                sortable          : [],
                texts             : {
                count             :"Mostrando registros del {from} al {to} de un total de {count}|{count} registros| Un registro",
                first             : "Primera",
                last              : "Última",
                filter            : "",
                filterPlaceholder : "Filtrar registros",
                limit             : "",
                page              : "Página:",
                noResults         : "No se encontraron resultados",
                filterBy          : "Filtrar tabla",
                loading           : "Cargando información",
                defaultOption     : "Seleccionar {column}",
                columns           : "Columnas"
                }
            }
        }
    },  
    methods: {
        getDataTable(url, params_data){   
            var self = this  
            this.$http.get(url, {params : params_data}).then(function(response){
                self.tableData = response.body
                self.loader = false
                }, function(){
                    alert('Error!');
                })            
        },
        getDirectDataTable(data){   
            var self = this  
            self.tableData = data                        
        }           
    }
}