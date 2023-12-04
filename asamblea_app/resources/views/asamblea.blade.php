<!DOCTYPE html>
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
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
                <i class="fa fa-bars"></i>
            </button>
            <div class="container">

                <div class="container">
                    @if($latestForm)
                        <h1>{{ $latestForm->name }}</h1>
                        <p>{{ $latestForm->description }}</p>
                        <ul>
                            <form action="{{ route('form.save-response', ['formId' => $latestForm->id, 'userId' => Auth::id()]) }}" method="POST">

                                @csrf
                                @foreach($latestForm->fields as $field)
                                @if(Auth::user()->rol == 'admin' || $field->is_active)
                                    <div class="mb-3">
                                        <li>
                                            <button type="button" class="btn btn-success btn-sm" onclick="activateField({{ $field->id }})">Activar</button>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deactivateField({{ $field->id }})">Desactivar</button>
                                            <br>
                                            <label style="color: black; font-size: 20px" class="form-label">{{ $field->label }}</label>
                                            
                                        </li>
                                        @if($field->type === 'multiple' || $field->type === 'yes_no')
                                            @if(is_array($field->options))
                                                @foreach($field->options as $option)
                                                    <div class="form-check">
                                                        <input type="radio" name="{{ 'field_' . $field->id }}" id="{{ 'field_' . $field->id . '_' . $loop->index }}" value="{{ $option }}">
                                                        <label for="{{ 'field_' . $field->id . '_' . $loop->index }}">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                                <button type="submit" class="btn btn-primary">Enviar Respuestas</button>
                            </form>
                        @else
                            <p>No se encontró el formulario.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script>
            function activateField(fieldId) {
    $.ajax({
        url: '/ruta-para-activar-pregunta/' + fieldId, // Reemplaza con tu ruta real
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}", // Token CSRF para seguridad
        },
        success: function(response) {
            // Aquí puedes manejar la respuesta. Por ejemplo:
            alert('Pregunta activada con éxito');
            // Actualizar el estado del botón o la interfaz según sea necesario
        },
        error: function(response) {
            // Manejar errores
            alert('Error al activar la pregunta');
        }
    });
}

function deactivateField(fieldId) {
    $.ajax({
        url: '/ruta-para-desactivar-pregunta/' + fieldId, // Reemplaza con tu ruta real
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}", // Token CSRF para seguridad
        },
        success: function(response) {
            // Manejar éxito
            alert('Pregunta desactivada con éxito');
            // Actualizar el estado del botón o la interfaz según sea necesario
        },
        error: function(response) {
            // Manejar errores
            alert('Error al desactivar la pregunta');
        }
    });
}

        </script>
        @extends('layouts.footer')
        @endsection
    </div>
</body>
</html>
