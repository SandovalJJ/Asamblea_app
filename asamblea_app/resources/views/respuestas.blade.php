<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Asamblea Coopserp - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    @extends('layouts.head')
</head>
<body>
    <div class="wrapper d-flex align-items-stretch">
        @extends('layouts.sidebar')
        @section('content')
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
                <i class="fa fa-bars"></i>
            </button>
            <div id="content" class="p-4 p-md-5">
                <h1>Resultados del Formulario</h1>
                @foreach ($formFields as $field)
                    <div class="mb-4">
                        <h2 class="fw-semibold">{{ $field->label }}</h2>
                        <!-- Mostrar porcentajes y respuestas -->
                        @if(isset($responsePercentages[$field->label]))
                            <table class="mb-2">
                                <tr>
                                    <th>Respuesta</th>
                                    <th>Porcentaje</th>
                                </tr>
                                @foreach ($responsePercentages[$field->label] as $answer => $percentage)
                                    <tr>
                                        <td>{{ $answer }}</td>
                                        <td>{{ $percentage }}%</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        <!-- Mostrar número de votos -->
                        <p>Número de votos: {{ $votesCountByQuestion[$field->label] ?? '0' }}</p>
                        <!-- Mostrar usuarios que faltan por votar -->
                        <p style="font-size: 15px; color: black">Personas que faltan por votar en esta pregunta</p>
                        <ul>
                            @forelse ($usersNotVotedByQuestion[$field->label] ?? [] as $userName)
                                <li>{{ $userName }}</li>
                            @empty
                                <li>Todos los usuarios han votado en esta pregunta.</li>
                            @endforelse
                        </ul>
                    </div>
                @endforeach
                <a href="{{ route('generar.pdf', ['formId' => $form->id]) }}" class="btn btn-primary">Generar PDF</a>
            </div>
        @extends('layouts.footer')
        @endsection
    </div>
</body>
</html>
