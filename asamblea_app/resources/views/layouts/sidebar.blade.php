<!-- resources/views/layouts/sidebar.blade.php -->

<!doctype html>
<html lang="en">
  <head>
    
    @include('layouts.head')
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      <nav id="sidebar">
    <div class="p-4 pt-5">
      <a class="img logo rounded-circle mb-5" style="background-image: url(images/R.png);"></a>
      <p style="text-align: center; font-size: 20px">¡Bienvenido {{ Auth::user()->name }}!</p>
        <ul class="list-unstyled components mb-5">
        <li >
            <a href="/admin" ><i class="bi bi-house"></i> Inicio</a>
            
        </li>
        <li>
            <a href="/usuarios"><i class="bi bi-people"></i> Usuarios</a>
        </li>
        <li>
        <a href="#"><i class="bi bi-card-checklist"></i> Formularios</a>
        
        </li>
        <li>
        <a href="#"><i class="bi bi-bar-chart-line"></i></i> Respuestas</a>
        </li>

        </ul>
        <ul class="list-unstyled components">
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-left"></i> Salir
                </a>
                <form id="logout-form" action="{{ route('cerrar') }}" method="POST" style="display: none;">
                @csrf
                </form>
            </li>
        </ul>
    </div>
    </nav>
<div id="content" class="p-4 p-md-5">
    @yield('content')
  </div>
</div>

@include('layouts.footer')
</body>
</html>