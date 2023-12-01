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
            'fields.*.options' => 'nullable|array', // Validación para las opciones de los campos
        ]);

        // Crear primero el formulario
        $form = Form::create(['name' => $validatedData['name']]);

        // Luego, asociar los campos con el formulario
        foreach ($validatedData['fields'] as $field) {
            if ($field['type'] == 'yes_no') {
                $options = ["Si", "No"];
            } else {
                $options = $field['type'] == 'multiple' ? $field['options'] : null;
            }

            $form->fields()->create([
                'label' => $field['label'],
                'type' => $field['type'],
                'options' => $options,
            ]);
        }


        return redirect()->back();
    }

    // En tu FormController o el controlador que maneje la visualización de los formularios

        public function show()
        {
            $formularios = Form::with('fields')->get();
            return view('show_formulario', compact('formularios'));
        }
    
    

}
