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
    
    

    public function mostrarRespuestas($formId)
    {
        $form = Form::with('fields')->findOrFail($formId);
        $responses = FormResponse::where('form_id', $formId)->get();
        $responseCounts = [];
        $responsePercentages = [];
        $totalUsers = User::count();
        $formFields = $form->fields;

        
        foreach ($responses as $response) {
            foreach ($response->response_data as $question => $answer) {
                $answer = $answer ?? 'Sin respuesta';
                $responseCounts[$question][$answer] = ($responseCounts[$question][$answer] ?? 0) + 1;
            }
        }

        foreach ($responseCounts as $question => $answers) {
            $totalAnswers = array_sum($answers);
            foreach ($answers as $answer => $count) {
                $percentage = $totalAnswers > 0 ? ($count / $totalAnswers) * 100 : 0;
                $responsePercentages[$question][$answer] = number_format($percentage, 2);
            }
        }

        // Conteos de votos por cada campo de formulario
        foreach ($form->fields as $field) {
            // Contar cuántos usuarios han votado por este campo específico
            $votesCount = $responses->filter(function ($response) use ($field) {
                return isset($response->response_data[$field->label]) && !is_null($response->response_data[$field->label]);
            })->count();
        
            // Calcular los que no han votado
            $noVotesCount = $totalUsers - $votesCount;
        
            // Agregar los conteos al array que se pasará a la vista
            $responseCounts[$field->label] = array_merge(
                $responseCounts[$field->label] ?? [],
                ['votes' => $votesCount, 'no_votes' => $noVotesCount]
            );
        }
        

        return view('respuestas', compact('responsePercentages', 'responseCounts', 'formFields'));
    }



}
