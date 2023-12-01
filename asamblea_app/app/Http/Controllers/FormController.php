<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    // Mostrar la vista para crear un nuevo formulario
    public function create()
    {
        return view('formularios');
    }

    // Guardar el nuevo formulario y sus campos en la base de datos
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string', // Validación para el nombre del formulario
            'fields' => 'required|array', // Validación para los campos del formulario
            'fields.*.label' => 'required|string', // Validación para la etiqueta de cada campo
            'fields.*.type' => 'required|string', // Validación para el tipo de cada campo
        ]);
        foreach ($validatedData['fields'] as $field) {
            $options = $field['type'] == 'multiple' ? $field['options'] : null;
    
            $form->fields()->create([
                'label' => $field['label'],
                'type' => $field['type'],
                'options' => $options
            ]);
        }

        $form = Form::create(['name' => $validatedData['name']]);

        foreach ($validatedData['fields'] as $field) {
            $form->fields()->create([
                'label' => $field['label'],
                'type' => $field['type']
            ]);
        }

        // Redirigir a donde sea necesario después de crear el formulario
        // return redirect()->route('ruta_de_redirección_después_de_crear');
    }

    // Otros métodos que puedas necesitar (edit, update, delete, etc.)
}