import Vue from 'vue'
import Toasted from 'vue-toasted';
Vue.use(Toasted)
Vue.toasted.register('APP_GENERAL_ERROR', 'Algo a salido mal. Vuelva a intentarlo.', {
    type : 'error',
    icon : 'Error',
    duration: 3000,
    singleton: true
})
Vue.toasted.register('APP_GENERAL_ERROR_FORM', 'Errores en el llenado del formulario', {
    type : 'error',
    icon : 'Error',
    duration: 3000,
    singleton: true
})
Vue.toasted.register('APP_GENERAL_SUCCESS', 'Operación realizada con exito.', {
    type : 'success',
    icon : 'Bien',
    duration: 3000,
    singleton: false
})
Vue.toasted.register('CHANGE_PASS_OK', 'Se ha cambiado la contraseña correctamente.', {
    type : 'success',
    icon : 'Bien',
    duration: 3000,
    singleton: false
})
Vue.toasted.register('FORM_UNIC_FIELD', 'Ya existen un registro con los datos ingresados.', {
    type : 'error',
    icon : 'Error',
    duration: 3000,
    singleton: true
})
