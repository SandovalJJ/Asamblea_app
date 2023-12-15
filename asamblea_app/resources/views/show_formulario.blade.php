<!doctype html>
<html lang="en">
<head>
    <title>Asamblea Coopserp - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .option-container {
        margin-top: 10px;
        border: 1px solid #ddd;
        padding: 5px;
        border-radius: 5px;
    }
    .option-container input {
        width: 95%;
        margin: 5px;
    }
    .question-label {
        color: black;
        font-weight: bold;
        font-size: 15px;
    }
    .modal-body {
    max-height: 400px; /* Ajusta este valor según tus necesidades */
    overflow-y: auto; /* Permite desplazamiento vertical si el contenido es más largo */
}
#buscarUsuario {
    margin-bottom: 10px;
}

/* Estilos para el botón de seleccionar todo */
.btn-seleccionar-todo {
    margin-bottom: 10px;
}
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @extends('layouts.head')
</head>
<body>
   

    <div class="wrapper d-flex align-items-stretch">
        @extends('layouts.sidebar')
        @section('content')
        <div id="content" class="p-4 p-md-5">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @auth
        @if(Auth::user()->rol == 'admin')
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
                <i class="fa fa-bars"></i>
              </button>
            <div class="container">
                <h1>Lista de Formularios</h1>
                @foreach($formularios as $formulario)
                    <div class="card mb-3 shadow">
                        <div class="card-header">
                            <h2 data-bs-toggle="collapse" href="#collapse{{ $formulario->id }}" role="button" aria-expanded="false" aria-controls="collapse{{ $formulario->id }}">
                                <i style="" class="bi bi-caret-down"></i> {{ $formulario->name }}
                            </h2>
                        </div>
                        <div id="collapse{{ $formulario->id }}" class="collapse">
                            <div class="card-body">
                                @foreach($formulario->fields as $field)
                                    <div class="mb-3">
                                        <label class="form-label">{{ $field->label }}</label>
                                        @if($field->type === 'multiple' || $field->type === 'yes_no')
                                            @if(is_array($field->options))
                                                @foreach($field->options as $option)
                                                    <div class="form-check">
                                                        <input  type="radio" name="{{ $field->label }}" id="{{ $option }}">
                                                        <label for="{{ $option }}">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                    
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="/respuestas-{{$formulario->id}}" class="btn btn-primary">Respuestas</a>
                        
                            <div>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUsuarios{{ $formulario->id }}">
                                    Asignar Usuarios
                                </button>
                        
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDesasignarUsuarios{{ $formulario->id }}">
                                    Desasignar Usuarios
                                </button>
                            </div>
                        </div>
                        
                        <div class="modal fade" id="modalUsuarios{{ $formulario->id }}" tabindex="-1" aria-labelledby="modalUsuariosLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalUsuariosLabel">Asignar Usuarios a {{ $formulario->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('assign-users', $formulario->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="text" class="form-control mb-3" id="buscarUsuario{{ $formulario->id }}" placeholder="Buscar usuario...">
                                            <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="seleccionarTodos({{ $formulario->id }})">Seleccionar Todos</button>
                                            @foreach($usuariosParaAsignarPorFormulario[$formulario->id] as $usuario)
                                            
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="user{{ $usuario->id }}_{{ $formulario->id }}" name="user_ids[]" value="{{ $usuario->id }}">
                                                    <label class="form-check-label" for="user{{ $usuario->id }}_{{ $formulario->id }}">{{ $usuario->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            
                                            <button type="submit" class="btn btn-success">Asignar Usuarios</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal" id="modalDesasignarUsuarios{{ $formulario->id }}" tabindex="-1" aria-labelledby="modalDesasignarUsuariosLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalUsuariosLabel">Desasignar Usuarios a {{ $formulario->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('unassign-users', $formulario->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                             <input type="text" class="form-control mb-3" onkeyup="filtrarUsuariosDesasignar(this.value, {{ $formulario->id }})" placeholder="Buscar usuario...">
                                             <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="seleccionarTodosDesasignar({{ $formulario->id }})">Seleccionar Todos</button>
                                             @foreach($usuariosAsignadosPorFormulario [$formulario->id] as $usuario)
                                            
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input user-checkbox" id="user{{ $usuario->id }}_{{ $formulario->id }}" name="user_ids[]" value="{{ $usuario->id }}">
                                                <label class="form-check-label" for="user{{ $usuario->id }}_{{ $formulario->id }}">{{ $usuario->name }}</label>
                                            </div>
                                            @endforeach
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Desasignar Usuarios Seleccionados</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
@endif
@endauth

        @extends('layouts.footer')
        <script>
            // Función para seleccionar/deseleccionar todos los checkboxes de usuarios
            function seleccionarTodosDesasignar(formularioId) {
                const checkboxes = document.querySelectorAll(`#modalDesasignarUsuarios${formularioId} .user-checkbox`);
                const todosSeleccionados = Array.from(checkboxes).every(checkbox => checkbox.checked);
                checkboxes.forEach(checkbox => checkbox.checked = !todosSeleccionados);
            }
            
            // Función para filtrar la lista de usuarios en el modal de desasignar
            function filtrarUsuariosDesasignar(filtro, formularioId) {
                const texto = filtro.toUpperCase();
                const labels = document.querySelectorAll(`#modalDesasignarUsuarios${formularioId} .form-check-label`);
            
                labels.forEach(label => {
                    const itemText = label.textContent || label.innerText;
                    const parentDiv = label.closest('.form-check');
                    if (itemText.toUpperCase().indexOf(texto) > -1) {
                        parentDiv.style.display = "";
                    } else {
                        parentDiv.style.display = "none";
                    }
                });
            }
            </script>
            
        <script>
            // Función para seleccionar o deseleccionar todos los usuarios
            function seleccionarTodos(formularioId) {
                let checkboxes = document.querySelectorAll('#modalUsuarios' + formularioId + ' .form-check-input');
                let todosSeleccionados = true;
                // Verifica si todos los checkboxes ya están seleccionados
                checkboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) todosSeleccionados = false;
                });
                // Establece todos los checkboxes al valor contrario
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = !todosSeleccionados;
                });
            }
            
            // Función para filtrar la lista de usuarios basado en la búsqueda
            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll('[id^=buscarUsuario]').forEach(inputBusqueda => {
                    inputBusqueda.addEventListener("keyup", function() {
                        let formularioId = this.id.replace('buscarUsuario', '');
                        let filtro = this.value.toUpperCase();
                        let modalBody = document.querySelector('#modalUsuarios' + formularioId + ' .modal-body');
                        let labels = modalBody.querySelectorAll('.form-check-label');
                        labels.forEach(function(label) {
                            let txtValue = label.textContent || label.innerText;
                            if (txtValue.toUpperCase().indexOf(filtro) > -1) {
                                label.closest('.form-check').style.display = "";
                            } else {
                                label.closest('.form-check').style.display = "none";
                            }
                        });
                    });
                });
            });
            </script>
        @endsection
    </div>
</body>
</html>
