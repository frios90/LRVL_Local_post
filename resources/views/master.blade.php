<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Proyecto Fejhu SpA. | Gestión interna</title>
  <link rel="stylesheet" href="{{ asset('intranet/css/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('web/vendors/bootstrap/bootstrap.min.css') }}">

  <link rel="stylesheet" href="{{ asset('intranet/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('faw/css/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('faw/css/brands.css') }}">
  <link rel="stylesheet"  href="{{ asset('faw/css/solid.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
</head>

<body class="sidebar-icon-only">
  <div id="app">    
    <div class="page-body-wrapper" >
      <app-menu></app-menu> 
      <div class="main-panel">    
        <app id="app-container-root" class="app-container-root"></app>          
      </div>  
    </div> 
  </div>
  <script src="{{ asset('intranet/js/app.js') }}"></script> 
  <script src="{{ asset('intranet/js/js/jquery-3.4.1.js') }}"></script>
  <script src="{{ asset('intranet/js/js/template.js') }}"></script>
  
  <script type="text/javascript">
</script>
  @yield('script')
</body>

</html>
