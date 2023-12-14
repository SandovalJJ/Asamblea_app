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

        .alert {
        font-size: 30px; /* Cambia esto según tus necesidades */
        }
    </style>
<style>
    .form-container {
        background-color: #f5f5f5;
        padding: 20px;
        border-radius: 10px;
    }

    .custom-form h2, .custom-form h3 {
        color: #333;
        margin-bottom: 15px;
    }

    .form-option {
        margin-bottom: 10px;
    }

    .btn-submit {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .next-question {
        display: block;
        margin-top: 20px;
        font-size: 18px;
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
            @auth
                @if(Auth::user()->rol == 'admin')
            <div class="container">
                <div class="container">
                    <br>

                    @if (session('error'))
                        <div class="alert alert-warning">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-warning">
                            {{ session('success') }}
                        </div>
                    @endif
                <div class="container">
                    <div>
                        @if(!$hasVoted)
                        <form action="{{ route('form.save-response', ['formId' => $currentForm->id]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 50px; color: black; font-weight: bold; text-align: center">{{ $currentForm->name }}</label>
                                <br>
                                <label class="form-label" style="font-size: 35px; color: black;"> {{ $field->label }}</label>
                                @if($field->type === 'multiple' || $field->type === 'yes_no')
                                    @if(is_array($field->options))
                                        @foreach($field->options as $option)
                                            <div class="form-check">
                                                <p type="radio" name="{{ 'field_' . $field->id }}" id="{{ 'field_' . $field->id . '_' . $loop->index }}" value="{{ $option }}">
                                                <label style="font-size: 25px" for="{{ 'field_' . $field->id . '_' . $loop->index }}">
                                                    ➣  {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                            <a  href="{{ route('form-field.toggle-active', $field->id) }}" class="btn btn-warning">
                                {{ $field->is_active ? 'Desactivar' : 'Activar' }} Campo
                            </a>

                        </form>
                    @else
                        <div class="alert alert-info">Ya has votado en este campo.</div>
                    @endif
                        <br>
                    </div>
                    <a style="font-size:20px; margin-right:25px; display: inline-block;" href="{{ route('form.show-field', ['formId' => $form->id, 'fieldIndex' => $fieldIndex - 1]) }}"><i class="bi bi-arrow-left-short"></i>Pregunta Anterior </a>
                    <a style="font-size:20px; margin-right:25px; display: inline-block;" href="{{ route('form.show-field', ['formId' => $form->id, 'fieldIndex' => $fieldIndex + 1]) }}">Siguiente Pregunta <i class="bi bi-arrow-right-short"></i></a>
                    </div>
                </div>
            </div>
            @endif
            @if(in_array(Auth::user()->rol, ['DELEGADO', 'suplente']))
            <div class="container">
                <!-- Mensajes de alerta -->
                @if (session('error'))
                    <div class="alert alert-warning">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
            
                <!-- Contenido del formulario -->

                <div class="form-container">
                    @if(!$hasVoted)
                        <form action="{{ route('form.save-response', ['formId' => $currentForm->id]) }}" method="POST" class="custom-form">
                            @csrf
                            <h2 style="font-size: 50px; color: black; font-weight: bold; text-align: center" class="form-title">{{ $currentForm->name }}</h2>
                            <h3 style="font-size: 35px" class="form-subtitle">{{ $field->label }}</h3>
            
                            <!-- Opciones del formulario -->
                            @if($field->type === 'multiple' || $field->type === 'yes_no')
                                @if(is_array($field->options))
                                    @foreach($field->options as $option)
                                        <div class="form-option">
                                            <input type="radio" name="{{ 'field_' . $field->id }}" id="{{ 'field_' . $field->id . '_' . $loop->index }}" value="{{ $option }}">
                                            <label style="font-size: 25px" for="{{ 'field_' . $field->id . '_' . $loop->index }}">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
            
                            <button style="font-size: 20px" type="submit" class="btn btn-primary mb-3">Enviar Respuesta</button>
                        </form>
                    @else
                        <div class="alert alert-info">Ya has votado en este campo.</div>
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 50px; color: black; font-weight: bold">{{ $currentForm->name }}</label>
                            <br>
                            <label class="form-label" style="font-size: 35px; color: black;">{{ $field->label }}</label>
                            @if($field->type === 'multiple' || $field->type === 'yes_no')
                                @if(is_array($field->options))
                                    @foreach($field->options as $option)
                                        <div class="form-check">
                                            <p type="radio" name="{{ 'field_' . $field->id }}" id="{{ 'field_' . $field->id . '_' . $loop->index }}" value="{{ $option }}">
                                            <label style="font-size: 25px" for="{{ 'field_' . $field->id . '_' . $loop->index }}">
                                                ➣ {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    @endif
                    </div>
                    <a style="font-size: 25px" href="{{ route('form.show-field', ['formId' => $form->id, 'fieldIndex' => $fieldIndex + 1]) }}">Siguiente Pregunta <i class="bi bi-arrow-right-short"></i></a>
                    </div>
                </div>
            </div>
            @endif
            @endauth
        </div>
        @extends('layouts.footer')
        @endsection
    </div>
</body>
</html>
