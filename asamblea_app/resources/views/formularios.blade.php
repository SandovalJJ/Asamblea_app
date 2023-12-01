<!doctype html>
<html lang="en">
  <head>
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
          
                      <div style="border-block-start: black" class="form-group field-group">
                          <label for="fields[0][label]">Pregunta 1:</label>
                          <input type="text" class="form-control" name="fields[0][label]" required>
          
                          <label for="fields[0][type]">Tipo:</label>
                          <select name="fields[0][type]" class="field-type">
                            <option value="yes_no">Sí / No</option>
                            <option value="multiple">Opción Múltiple</option>
                        </select>
                        <div class="options-container" style="display: none;">
                          <label>Opciones:</label>
                          <br>

                          <button type="button" class="add-option  btn-warning">Añadir Opción</button>
                          <button type="button" class="remove-option btn-danger">Eliminar Última Opción</button>

                          
                          <div class="option-container"><label>Opción 1: </label><input type="text" name="fields[0][options][]"></div>
                          
                          
                      </div>
                      </div>
                  </div>
          
                  <button type="button" class="btn btn-secondary add-field">Añadir Campo</button>
                  <button type="submit" class="btn btn-primary">Guardar Formulario</button>
              </form>
          </div>
          
          <script>
            function initializeFieldEvents(fieldGroup) {
                let select = fieldGroup.querySelector('.field-type');
                let optionsContainer = fieldGroup.querySelector('.options-container');
                let removeButton = fieldGroup.querySelector('.remove-option');
                    removeButton.addEventListener('click', function () {
                        removeLastOption(optionsContainer);
                    });
                
                
                select.addEventListener('change', function () {
                    if (this.value === 'multiple') {
                        optionsContainer.style.display = 'block';
                        if (optionsContainer.innerHTML.trim() === '') {
                            addOptionInput(optionsContainer, fieldGroup);
                        }
                    } else {
                        optionsContainer.style.display = 'none';
                    }
                });
        
                let addButton = fieldGroup.querySelector('.add-option');
                addButton.addEventListener('click', function () {
                    addOptionInput(optionsContainer, fieldGroup);
                });
            }
        
            function addOptionInput(optionsContainer, fieldGroup) {
                let optionNumber = optionsContainer.getElementsByClassName('option-container').length + 1;
                let optionDiv = document.createElement('div');
                optionDiv.classList.add('option-container');
        
                let optionLabel = document.createElement('label');
                optionLabel.textContent = `Opción ${optionNumber}: `;
                optionDiv.appendChild(optionLabel);
        
                let newOption = document.createElement('input');
                newOption.type = 'text';
                let fieldIndex = fieldGroup.querySelector('.field-type').name.match(/\d+/)[0];
                newOption.name = `fields[${fieldIndex}][options][]`;
        
                optionDiv.appendChild(newOption);
                optionsContainer.appendChild(optionDiv);
            }

            function removeLastOption(optionsContainer) {
                let options = optionsContainer.getElementsByClassName('option-container');
                if (options.length > 1) {
                    optionsContainer.removeChild(options[options.length - 1]);
                }
            }
        
            function createNewField(fieldIndex) {
                let fieldGroup = document.createElement('div');
                fieldGroup.className = 'form-group field-group';
                fieldGroup.innerHTML = `
                    <label class="question-label" for="fields[${fieldIndex}][label]">Pregunta ${fieldIndex + 1}:</label>
                    <input type="text" class="form-control" name="fields[${fieldIndex}][label]" required>
                    <label for="fields[${fieldIndex}][type]">Tipo:</label>
                    <select name="fields[${fieldIndex}][type]" class="field-type">
                        <option value="yes_no">Sí / No</option>
                        <option value="multiple">Opción Múltiple</option>
                    </select>
                    <div class="options-container" style="display: none;">
                      <label>Opciones:</label>
        <button type="button" class="add-option  btn-warning">Añadir Opción</button>
        <button type="button" class="remove-option btn-danger">Eliminar Última Opción</button>
                        <div class="option-container">
                            <label>Opción 1: </label>
                            <input type="text" name="fields[${fieldIndex}][options][]">
                        </div>
                    </div>
                `;
        
                return fieldGroup;
            }
        
            document.addEventListener('DOMContentLoaded', function () {
                let fieldCount = 1;
        
                document.querySelector('.add-field').addEventListener('click', function () {
                    let container = document.querySelector('.fields');
                    let newField = createNewField(fieldCount);
                    container.appendChild(newField);
                    initializeFieldEvents(newField);
        
                    fieldCount++;
                });
        
                // Aplicar la clase a la etiqueta de la primera pregunta
                let firstFieldLabel = document.querySelector('.field-group label[for="fields[0][label]"]');
                firstFieldLabel.classList.add('question-label');
        
                initializeFieldEvents(document.querySelector('.field-group'));
            });
        </script>

        
        
        
        

    @extends('layouts.footer')
    @endsection
  </body>
</html>