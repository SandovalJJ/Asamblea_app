<!doctype html>
<html lang="en">
  <head>
  	<title>Asamblea Coopserp - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    @extends('layouts.head')
  </head>
  <body>
		<div class="wrapper d-flex align-items-stretch">
			@extends('layouts.sidebar')
            @section('content')
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
            </button>
        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">


        <h2 class="mb-4">¡Bienvenid@ al software de asamblea, {{ Auth::user()->name }}!</h2>
            <div class="container">
              <h1>Crear Nuevo Formulario</h1>
              <form method="POST" action="{{ route('forms.store') }}">
                  @csrf
          
                  <div class="form-group">
                      <label for="name">Nombre del Formulario:</label>
                      <input type="text" class="form-control" id="name" name="name" required>
                  </div>
          
                  <div class="fields">
                      <h3>Campos del Formulario</h3>
          
                      <div class="form-group field-group">
                          <label for="fields[0][label]">Etiqueta:</label>
                          <input type="text" class="form-control" name="fields[0][label]" required>
          
                          <label for="fields[0][type]">Tipo:</label>
                          <select name="fields[0][type]" class="field-type">
                            <option value="yes_no">Sí / No</option>
                            <option value="multiple">Opción Múltiple</option>
                        </select>
                        <div class="options-container" style="display: none;">
                          <label>Opciones:</label>
                          <input type="text" name="fields[0][options][]">
                          <button type="button" class="add-option">Añadir Opción</button>
                      </div>
                      </div>
                  </div>
          
                  <button type="button" class="btn btn-secondary add-field">Añadir Campo</button>
                  <button type="submit" class="btn btn-primary">Guardar Formulario</button>
              </form>
          </div>
          
          <script>
            function initializeFieldEvents(fieldGroup) {
                // Evento para mostrar/ocultar opciones
                let select = fieldGroup.querySelector('.field-type');
                select.addEventListener('change', function () {
                    let optionsContainer = fieldGroup.querySelector('.options-container');
                    optionsContainer.style.display = this.value === 'multiple' ? 'block' : 'none';
                });
        
                // Evento para añadir opciones
                let addButton = fieldGroup.querySelector('.add-option');
                addButton.addEventListener('click', function () {
                    let optionsContainer = fieldGroup.querySelector('.options-container');
        
                    let optionDiv = document.createElement('div'); // Crear un nuevo div para la opción
                    let newOption = document.createElement('input');
                    newOption.type = 'text';
                    let fieldIndex = fieldGroup.querySelector('.field-type').name.match(/\d+/)[0];
                    newOption.name = `fields[${fieldIndex}][options][]`;
        
                    optionDiv.appendChild(newOption); // Añadir la entrada al div
                    optionsContainer.appendChild(optionDiv); // Añadir el div al contenedor de opciones
                });
            }
        
            document.addEventListener('DOMContentLoaded', function () {
                let fieldCount = 1;
        
                document.querySelector('.add-field').addEventListener('click', function () {
                    let container = document.querySelector('.fields');
                    let newField = document.querySelector('.field-group').cloneNode(true);
        
                    newField.innerHTML = newField.innerHTML.replace(/\[0\]/g, `[${fieldCount}]`);
                    container.appendChild(newField);
                    initializeFieldEvents(newField);
        
                    fieldCount++;
                });
        
                initializeFieldEvents(document.querySelector('.field-group'));
            });
        </script>
        
        
        
        

    @extends('layouts.footer')
    @endsection
  </body>
</html>