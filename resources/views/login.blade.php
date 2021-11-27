

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Proyecto Fejhu</title>
      <link rel="icon" href="web/img/Fevicon.png" type="image/png">
      <link rel="stylesheet" href="web/vendors/bootstrap/bootstrap.min.css">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="icon" type="image/png" href="web/login/src/images/icons/favicon.ico"/>
      <link rel="stylesheet" type="text/css" href="web/login/src/vendor/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/fonts/iconic/css/material-design-iconic-font.min.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/vendor/animate/animate.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/vendor/css-hamburgers/hamburgers.min.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/vendor/animsition/css/animsition.min.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/vendor/select2/select2.min.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/vendor/daterangepicker/daterangepicker.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/css/util.css">
      <link rel="stylesheet" type="text/css" href="web/login/src/css/main.css">
   </head>
   <body style="background-color: #999999;">
      <div class="limiter">
         <div class="container-login100">
            <div class="style-login-more" style="background-image: url('web/login/src/images/login.jpg');"></div>
            <div class="wrap-login100 p-l-50 p-r-50 p-t-72 p-b-50">
               <form class="style-login-form validate-form"  id="access-login">
                  <span class="style-login-form-title p-b-59">
                  Acceso
                  </span>
                  @csrf
                  <div class="wrap-inputs-of-login validate-input" data-validate = "Ingrese un correo valido: ex@abc.xyz">
                     <span class="label-inputs-of-login">Usuario / Correo</span>
                     <input class="inputs-of-login" type="text" id="email" name="email" placeholder="Correo del usuario...">
                     <span id="error-login" style="display: none; color: red; font-size: 11px;">No pudieron validarse las credenciales. Reintente</span>
                  </div>
                  <div class="wrap-inputs-of-login validate-input" data-validate = "La contraseña el requerida">
                     <span class="label-inputs-of-login">Contraseña</span>
                     <input class="inputs-of-login" type="password" id="password" name="password" placeholder="*************">
                  </div>
                  <div class="container-style-login-form-btn">
                     <div class="wrap-style-login-form-btn">
                        <div class="style-login-form-bgbtn"></div>
                        <button class="style-login-form-btn">
                        Ingresar
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </body>
</html>
<script src="web/login/src/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="web/login/src/vendor/animsition/js/animsition.min.js"></script>
<script src="web/login/src/vendor/bootstrap/js/popper.js"></script>
<script src="web/login/src/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="web/login/src/vendor/select2/select2.min.js"></script>
<script src="web/login/src/vendor/daterangepicker/moment.min.js"></script>
<script src="web/login/src/vendor/daterangepicker/daterangepicker.js"></script>
<script src="web/login/src/vendor/countdowntime/countdowntime.js"></script>
<script src="web/login/src/js/main.js"></script>
<script>
   $(document).ready(function() 
       {
           $('#access-login').on('submit', function(event){
               $('#error-login').css('display', 'none')
               event.preventDefault();
               var send = {
                   "_token"   : document.getElementsByName('_token')[0].value,
                   "email"    : $('#email').val(),
                   "password" : $('#password').val(),               
               }
               var r = $.ajax({
                   data: send,
                   type: "POST",
                   dataType: "json",
                   url: "{{ route('/sing-in') }}",
                           
                   success: function (data) {                    
                       window.location.replace(data)
                   },     
                   
                   error: function (data) {                                        
                           $('#error-login').css('display', 'block')
                           $('#error-login').html('No pudieron validarse las credenciales. Reintente')                                        
                   }
               })
           })
       })
</script>
<style>
</style>

