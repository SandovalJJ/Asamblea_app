@php
$lastForm = \App\Models\Form::latest()->first();
$lastFormId = $lastForm ? $lastForm->id : null;
$firstFieldIndex = $lastForm ? 1 : null; // Asumir que el primer campo tiene índice 1
@endphp


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
            @auth
    @if(Auth::user()->rol == 'admin')
        </li>
        <li>
            <a href="/usuarios"><i class="bi bi-people"></i> Usuarios</a>
        </li>
        <li>
          <a href="{{ route('form.create') }}"><i class="bi bi-input-cursor-text"></i></i> Crear Formulario</a>
          </li>
        <li>
        <a href="/show_formulario"><i class="bi bi-card-checklist"></i> Formularios</a>
        
        </li>
        @endif
        @if(in_array(Auth::user()->rol, ['DELEGADO', 'SUPLENTE','admin']))
        <li>
          @if($lastFormId && $firstFieldIndex)
              @php
                  $isUserAssigned = \App\Models\Form::find($lastFormId)->assignedUsers->contains(Auth::id());
              @endphp
              @if(Auth::user()->rol === 'admin' || $isUserAssigned)

                  <a href="{{ route('form.show-field', ['formId' => $lastFormId, 'fieldIndex' => $firstFieldIndex]) }}"><i class="bi bi-layout-text-window-reverse"></i> Formulario actual</a>
              @else
                  <span><i class="bi bi-layout-text-window-reverse"></i> No estás asignado a ningún formulario actualmente</span>
              @endif
          @else
              <span><i class="bi bi-layout-text-window-reverse"></i> No hay formularios disponibles</span>
          @endif
      </li>
      @endif
      @endauth
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