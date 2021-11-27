<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Projecto Fejhu Intranet</title>
        <link rel="icon" href="web/img/Fevicon.png" type="image/png">
        <link rel="stylesheet" href="web/vendors/bootstrap/bootstrap.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            body {
                background: rgb(29,51,68);
	            background: linear-gradient(90deg, rgba(29,51,68,1) 0%, rgba(49,79,86,1) 47%, rgba(61,95,96,1) 97%, rgba(61,95,96,1) 98%, rgba(87,131,119,1) 99%, rgba(170,246,193,1) 100%); 

            }
            
            .section-login {
                width: 40%;
                padding: 50px 75px;
                margin: 70px auto
            }

            .titlo-login {
                color: #fff;
                font-size: 18px;
                letter-spacing: 2px;
                font-weight: 600;
                text-align: center;
                margin-bottom: 40px;
            }
            .titulo-input-login{
                color: #fff;
                font-size: 12px;
                letter-spacing: 1.5px;
                margin-bottom: 15px;
            }

            input[type="email"], input[type="password"] {
                border: 0px!important;
                border-bottom: solid 3px #3cbc64!important; 
                color: #3cbc64!important;

            }

            input[type="password"] {
                margin-bottom: 50px;
                
            }

            .btn-recuperar-pass {
                background: transparent;
            }

            .btn-login {
                background: transparent;
            }
                        
        </style>
    </head>
    <body>
        <section class="section-login">
            <div class="container">
                <h3 class="titlo-login">Acceso a ferreterias</h3>
                <form class="login_form" id="access-login" >
                    @csrf    
                    <div class="col-sm-12 div-input-login-user">
                        <label class="titulo-input-login" for="">Ingrese su usuario</label>
                        <input class="form-control input-login" id="email" name="email" placeholder="Ingrese su nombre de usuario" type="email">
                        <span id="error-login" style="display: none; color: red; font-size: 11px;">No pudieron validarse las credenciales. Reintente</span>
                    </div>
                    <div class="col-sm-12 div-input-login-pass">
                        <label class="titulo-input-login" for="">ingrese su contraseña</label>
                        <input class="form-control input-login" id="password" name="password" placeholder="Ingrese su contraseña" type="password">
                    </div>							
                    <div class="col-md-12 div-login-access">
                        <input type="submit" class="btn-recuperar-pass" value="Recuperar contraseña"/>
                        <input type="submit" class="btn-login" value="Ingresar"/>
                    </div>
                </form>
            </div>
        </section>    
        
    </body>   
</html>
<script src="web/vendors/jquery/jquery-3.2.1.min.js"></script>
<script src="web/vendors/bootstrap/bootstrap.bundle.min.js"></script>
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