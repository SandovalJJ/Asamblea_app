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
                        <div class="d-flex"><a href="/respuestas-{{$formulario->id}}" class="btn btn-primary w-25 fs-5 fw-semibold mx-auto mb-3 mt-3">Respuestas</a></div>
                    </div>
                @endforeach
            </div>
        </div>
        <a href="{{ route('grafico.generar') }}" class="btn btn-primary">Generar Gráfico</a>

        @extends('layouts.footer')
        @endsection
    </div>
</body>
</html>
