<!doctype html>
<html lang="en">
  <head>
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
    @foreach ($responsePercentages as $question => $answers)
        <h2>{{ $question }}</h2>
        <table>
            <tr>
                <th>Respuesta</th>
                <th>Porcentaje</th>
            </tr>
            @foreach ($answers as $answer => $percentage)
                <tr>
                    <td>{{ $answer }}</td>
                    <td>{{ $percentage }}%</td>
                </tr>
            @endforeach
        </table>
    @endforeach
		</div>
    @extends('layouts.footer')
    @endsection
  </body>
</html>