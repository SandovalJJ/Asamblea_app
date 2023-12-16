<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Asamblea Coopserp - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .form-field-container {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-field-label {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .response-table {
            width: 100%;
            margin-top: 10px;
        }
        .response-table th {
            background-color: #f2f2f2;
        }
        .response-table td, .response-table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .voters-list {
            font-size: 15px;
            color: black;
            margin-top: 10px;
        }
    </style>
    @extends('layouts.head')
</head>
<body>
    @auth
    @if(Auth::user()->rol == 'admin')
    <div class="wrapper d-flex align-items-stretch">
        @extends('layouts.sidebar')
        @section('content')
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
                <i class="fa fa-bars"></i>
            </button>
            <a href="{{ route('generar.pdf', ['formId' => $form->id]) }}" class="btn btn-primary">Generar PDF</a>
            <div id="content" class="p-4 p-md-5">
                <h1> {{$form->name}} </h1>
                @foreach ($formFields as $field)
                    <?php $fieldKey = 'field_' . $field->id; ?>

                    <div class="form-field-container">
                        <h2 class="form-field-label">{{ $field->label }}</h2>
                        <!-- Mostrar porcentajes y respuestas -->
                        @if(isset($responsePercentages[$fieldKey]))
                            <table class="response-table">
                                <tr>
                                    <th>Respuesta</th>
                                    <th>Porcentaje</th>
                                </tr>
                                @foreach ($responsePercentages[$fieldKey] as $answer => $percentage)
                                    <tr>
                                        <td>{{ $answer }}</td>
                                        <td>{{ $percentage }}%</td>
                                    </tr>
                                @endforeach
                            </table>
                            <canvas id="chart{{$field->id}}"></canvas>
                        @endif
                        <!-- Mostrar número de votos -->
                        <p>Número de votos: {{ $votesCountByQuestion[$fieldKey] ?? '0' }}</p>
                        <!-- Mostrar usuarios que faltan por votar -->
                        <div class="voters-list">
                            <p>Personas que faltan por votar en esta pregunta:</p>
                            <ul>
                                @forelse ($usersNotVotedByQuestion[$fieldKey] ?? [] as $userName)
                                    <li>{{ $userName }}</li>
                                @empty
                                    <li>Todos los participantes han votado en esta pregunta.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @endforeach
                
            </div>
             <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @extends('layouts.footer')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @foreach ($formFields as $field)
                var ctx = document.getElementById('chart{{$field->id}}').getContext('2d');
                var chartData = @json($chartData[$field->id]);
    
                new Chart(ctx, {
                    type: 'bar', // Puedes cambiar esto a 'line', 'pie', etc. según tus preferencias
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Número de votos',
                            data: chartData.data,
                            backgroundColor: 'rgba(0, 123, 255, 0.5)', // Color de fondo
                            borderColor: 'rgba(0, 123, 255, 1)', // Color del borde
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                @endforeach
            });
        </script>
        @endsection
    </div>
    @endif
    @endauth
</body>
</html>
