<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Models\FormResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                $options = ["SI", "NO"];
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

    public function show()
    {
        $formularios = Form::with('fields')->get();
        return view('show_formulario', compact('formularios'));
    }

    public function showLatestForm()
    {
        $latestForm = Form::with('fields')->latest()->first();

        if (!$latestForm) {
            return redirect()->back()->with('error', 'No hay formularios disponibles.');
        }

        return view('asamblea', compact('latestForm'));
    }

    public function saveResponse(Request $request, $formId, $userId) {
    // Obtén el formulario y el usuario
    $form = Form::findOrFail($formId);
    $user = User::findOrFail($userId);

    // Recopila las respuestas del formulario
    $response_data = [];

    foreach ($form->fields as $field) {
        $field_name = 'field_' . $field->id;
        $response_data[$field_name] = $request->input($field_name);
    }

    // Guarda la respuesta en la base de datos
    FormResponse::create([
        'form_id' => $formId,
        'user_id' => $userId,
        'response_data' => $response_data,
    ]);

    return redirect()->back();
    }

    public function activate($id)
    {
        $field = FormField::findOrFail($id);
        $field->is_active = true;
        $field->save();

        return response()->json(['message' => 'Pregunta activada con éxito']);
    }

    public function deactivate($id)
    {
        $field = FormField::findOrFail($id);
        $field->is_active = false;
        $field->save();

        return response()->json(['message' => 'Pregunta desactivada con éxito']);
    }

public function showResponses($formId)
{
    // Obtén el formulario específico por su ID
    $form = Form::with('fields')->findOrFail($formId);

    // Obtén las respuestas asociadas a ese formulario
    $responses = FormResponse::where('form_id', $formId)->with('user')->get();

    return view('respuestas', compact('form', 'responses'));
}


}
