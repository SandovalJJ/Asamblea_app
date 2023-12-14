<!doctype html>
<html lang="en">
  <head>
  	<title>Asamblea Coopserp - Admin</title>
    @extends('layouts.head')
  </head>
  <body>
		
		<div class="wrapper d-flex align-items-stretch">
			@extends('layouts.sidebar')
            @section('content')
        <!-- Page Content  -->
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
          <i class="fa fa-bars"></i>
          <span class="sr-only">Toggle Menu</span>
        </button>
      <div id="content" class="p-4 p-md-5">



        <h2 class="mb-4">Bienvenido al software de asamblea</h2>
        <p></p>
      
      </div>
		</div>

    @extends('layouts.footer')
    @endsection
  </body>
</html>