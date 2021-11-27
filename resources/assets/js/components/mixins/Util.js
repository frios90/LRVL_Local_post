
export default { 
    data () {
        return {
            csrf    : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            loader  : true,
            msg_return_menu: 'Presione para ir al menu anterior. Si no ha guardado los cambios serán descartados',
            msg_save_store: 'Presione crear el nuevo registro',
            msg_save_update: 'Presione para actualizar los datos del registro',
            Auth: ""
        }
    },
    created () {
        this.getDataAuth()
    },
    mounted () {
    },  
    methods: {    
        validateNumber (number) {
            return parseInt(number)
        },
        isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault()
            } else {
                return true;
            }
        },
        isDate: function(evt) {
            evt.preventDefault()
        },  
        getDataAuth() {
            var self = this
            this.$http.get('/user-data-auth').then(function(response) {                
                self.Auth = response.body
            }, function() {
                this.$toasted.global.APP_GENERAR_ERROR()
            })
        },  
        formatSearchSelect(array) {
            var neo_array = []            
            array.forEach(function(val, ind) {
                if (val.label) {
                    var set =  {
                        value: val.id,
                        text: val.label
                    }
                } else {
                    var set =  {
                        value: val.id,
                        text: val.name
                    }
                }
                
                neo_array.push(set)
            })
            return neo_array
            
        }
    },
    filters: {
        mixinGenericError (response) {
            if (response.status === 422) {
                this.$toasted.global.APP_GENERAL_ERROR_FORM()
                this.errors = response.body.errors
            }else{
                this.$toasted.global.APP_GENERAL_ERROR()
            }
        },  
        upper (string) {
            var string =  string.toLowerCase()
            return string.replace(/\b\w/g, l => l.toUpperCase())
        },
        dateInverted: function (date) {
            var date = date.substr(0,10)               
            return date.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3-$2-$1')
        },
        dateTimeInverted: function (date) {            
            var dateSplit = date.split(" ")
            date = dateSplit[0].substr(0,10)               
            return date.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3-$2-$1') + " " + dateSplit[1]
        },
        isNumber: function(evt) {
            evt = (evt) ? evt : window.event
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault()
            } else {
                return true;
            }
        }
    }
}