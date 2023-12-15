<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Models\FormResponse;
use App\Models\User;
use Dompdf\Dompdf;
use FPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PDF;

class FormController extends Controller
{
    public function create()
    {
        return view('formularios');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string', 
            'fields' => 'required|array', 
            'fields.*.label' => 'required|string', 
            'fields.*.type' => 'required|string', 
            'fields.*.options' => 'nullable|array', 
        ]);
        $form = Form::create(['name' => $validatedData['name']]);
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
        $formularios = Form::with('assignedUsers')->get();
        $todosLosUsuarios = User::all();
        $usuariosAsignadosPorFormulario = [];
        $usuariosParaAsignarPorFormulario = [];
    
        foreach ($formularios as $formulario) {
            $usuariosAsignadosIds = $formulario->assignedUsers->pluck('id');
            $usuariosAsignados = $todosLosUsuarios->whereIn('id', $usuariosAsignadosIds);
            $usuariosParaAsignar = $todosLosUsuarios->whereNotIn('id', $usuariosAsignadosIds);
            
            $usuariosAsignadosPorFormulario[$formulario->id] = $usuariosAsignados;
            $usuariosParaAsignarPorFormulario[$formulario->id] = $usuariosParaAsignar;
        }
        return view('show_formulario', compact('formularios', 'usuariosAsignadosPorFormulario', 'usuariosParaAsignarPorFormulario'));
    }
    
    
    public function showFieldByIndex($formId, $fieldIndex)
    {
        $currentForm = Form::with(['fields' => function($query) use ($fieldIndex) {
            $query->skip($fieldIndex - 1)->take(1);
        }, 'assignedUsers'])->findOrFail($formId);
    
        $userId = Auth::id();
        $userRole = Auth::user()->rol;
    
        // Verificar si el usuario está asignado al formulario
        
        if (!$currentForm->assignedUsers->contains($userId)) {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a este formulario.');
        }
        $form = Form::with(['fields' => function($query) use ($fieldIndex) {
            $query->skip($fieldIndex - 1)->take(1);
        }])->findOrFail($formId);

        if ($form->fields->isEmpty()) {
            return redirect()->back()->with('error', 'No hay más campos en el formulario.');
        }

        $field = $form->fields->first();

        if ($userRole !== 'admin' && !$field->is_active) {
            return redirect()->back()->with('error', 'La siguiente pregunta aún no está habilitada, por favor espere a que el administrador la habilite.');
        }

        $fieldId = 'field_' . $field->id;

        $response = FormResponse::where('form_id', $formId)
                                ->where('user_id', $userId)
                                ->first();

        $hasVoted = $response && isset($response->response_data[$fieldId]);
        return view('asamblea', compact('form', 'field', 'fieldIndex', 'hasVoted', 'currentForm'));
    }


    public function saveResponse(Request $request, $formId)
    {
        $userId = Auth::id();
    
        // Buscar una respuesta existente específicamente para este formulario y usuario
        $response = FormResponse::firstOrNew([
            'form_id' => $formId,
            'user_id' => $userId
        ]);
    
        // Recuperar los datos existentes o inicializar un arreglo vacío si es nuevo
        $responseData = $response->response_data ?? [];
    
        // Añadir o actualizar la respuesta para cada campo del formulario
        foreach ($request->except(['_token']) as $fieldId => $answer) {
            $responseData[$fieldId] = $answer;
        }
    
        $response->response_data = $responseData;
        $response->save();
    
        return redirect()->back()->with('success', 'Respuesta guardada correctamente');
    }

    public function mostrarRespuestas($formId)
    {
        $form = Form::with(['fields', 'assignedUsers'])->findOrFail($formId);
        $formUsers = $form->assignedUsers;
        $formUsersIds = $formUsers->pluck('id')->toArray();
        $responses = FormResponse::where('form_id', $formId)->get();
    
        $formFields = $form->fields;
    
        $responseCounts = [];
        $responsePercentages = [];
        $usersNotVotedByQuestion = [];
        $votesCountByQuestion = [];
    
        foreach ($formFields as $field) {
            $fieldKey = 'field_' . $field->id;
    
            // Inicializar conteo de respuestas
            foreach ($field->options as $option) {
                $responseCounts[$fieldKey][$option] = 0;
            }
    
            // Contar respuestas reales
            foreach ($responses as $response) {
                if (isset($response->response_data[$fieldKey])) {
                    $answer = $response->response_data[$fieldKey];
                    $responseCounts[$fieldKey][$answer]++;
                }
            }
    
            $totalAnswers = array_sum($responseCounts[$fieldKey]);
            $votesCountByQuestion[$fieldKey] = $totalAnswers;
    
            // Calcular porcentajes
            foreach ($responseCounts[$fieldKey] as $answer => $count) {
                $percentage = $totalAnswers > 0 ? ($count / $totalAnswers) * 100 : 0;
                $responsePercentages[$fieldKey][$answer] = number_format($percentage, 2);
            }
    
            // Calcular usuarios que no han votado en esta pregunta
            $usersWhoHaveVoted = $responses->filter(function ($response) use ($fieldKey) {
                return isset($response->response_data[$fieldKey]);
            })->pluck('user_id')->toArray();
    
            $usersNotVotedByQuestion[$fieldKey] = $formUsers->reject(function ($user) use ($usersWhoHaveVoted) {
                return in_array($user->id, $usersWhoHaveVoted);
            })->values()->mapWithKeys(function ($user) {
                return [$user->id => $user->name];
            });
        }
    
        return view('respuestas', compact('responsePercentages', 'responseCounts', 'formFields', 'usersNotVotedByQuestion', 'votesCountByQuestion', 'form'));
    }
    
    public function generarGrafico() {
        $datos = [50, 30, 20]; // Ejemplo de datos para el gráfico

        $ancho = 400;
        $alto = 300;
        $imagen = imagecreatetruecolor($ancho, $alto);

        // Colores
        $fondo = imagecolorallocate($imagen, 255, 255, 255);
        $negro = imagecolorallocate($imagen, 0, 0, 0);
        $colores = [
            imagecolorallocate($imagen, 220, 57, 18),
            imagecolorallocate($imagen, 255, 153, 0),
            imagecolorallocate($imagen, 51, 102, 204),
        ];

        // Fondo
        imagefilledrectangle($imagen, 0, 0, $ancho, $alto, $fondo);

        // Barras
        $max_valor = max($datos);
        $x = 50;
        $ancho_barra = 40;
        $espaciado = 30;

        foreach ($datos as $indice => $valor) {
            $altura = ($valor / $max_valor) * ($alto - 60);
            imagefilledrectangle($imagen, $x, $alto - $altura - 30, $x + $ancho_barra, $alto - 30, $colores[$indice]);
            $x += $ancho_barra + $espaciado;
        }

        // Borde
        imagerectangle($imagen, 0, 0, $ancho - 1, $alto - 1, $negro);

        // Guardar la imagen
        $ruta_imagen = public_path('graficos/grafico.png');
        imagepng($imagen, $ruta_imagen);
        imagedestroy($imagen);

        // Aquí puedes decidir si devuelves la ruta de la imagen o la descarga directamente
        return response()->download($ruta_imagen);
    }

    public function generarPDF($formId)
    {
        $form = Form::with(['fields', 'assignedUsers'])->findOrFail($formId);
        $responses = FormResponse::where('form_id', $formId)->get();
        $pdf = new Fpdf();
    
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
    
        $margenIzquierdo = 10;
        $margenDerecho = 10;
        $anchoPagina = $pdf->GetPageWidth();
        $anchoTexto = $anchoPagina - $margenIzquierdo - $margenDerecho;
    
        $pdf->MultiCell($anchoTexto, 10, utf8_decode($form->name), 0, 'C', false);
        $pdf->Ln(5);
    
        $pdf->SetFont('Arial', '', 12);
        foreach ($form->fields as $field) {
            $fieldKey = 'field_' . $field->id;
            $pdf->SetFillColor(230, 230, 230);
            $pdf->MultiCell(0, 10, utf8_decode($field->label), 1, 'L', true);
            $pdf->Ln(2);
    
            // Inicializar conteo de respuestas
            $answerCounts = [];
            if (isset($field->options) && is_array($field->options)) {
                foreach ($field->options as $option) {
                    $answerCounts[$option] = 0;
                }
            }
    
            // Contar respuestas
            foreach ($responses as $response) {
                if (isset($response->response_data[$fieldKey]) && isset($answerCounts[$response->response_data[$fieldKey]])) {
                    $answerCounts[$response->response_data[$fieldKey]]++;
                }
            }
    
            $totalAnswers = array_sum($answerCounts);
            foreach ($answerCounts as $answer => $count) {
                $percentage = $totalAnswers > 0 ? ($count / $totalAnswers) * 100 : 0;
                $pdf->MultiCell(0, 10, utf8_decode("Respuesta: $answer - Votos: $count - " . number_format($percentage, 2) . '%'), 0, 'L');
            }
    
            // Personas que faltan por votar en esta pregunta
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(0, 10, "Personas que faltan por votar en esta pregunta:", 0, 1);
    
            // Obtener IDs de usuarios asignados que no han votado en esta pregunta
            $assignedUserIds = $form->assignedUsers->pluck('id')->toArray();
            $usersWhoVoted = $responses->filter(function ($response) use ($fieldKey) {
                return isset($response->response_data[$fieldKey]);
            })->pluck('user_id')->toArray();
            $usersWhoHaveNotVoted = User::whereIn('id', $assignedUserIds)
                                          ->whereNotIn('id', $usersWhoVoted)
                                          ->get();
    
            foreach ($usersWhoHaveNotVoted as $user) {
                $pdf->Cell(0, 10, utf8_decode($user->name), 0, 1);
            }
    
            $pdf->Ln(5); // Espacio antes de la próxima pregunta
        }
    
        $pdf->Output('D', 'resultados_asamblea.pdf');
    }
    
    public function toggleActive($fieldId)
        {
            $field = FormField::findOrFail($fieldId);
            $field->is_active = !$field->is_active;
            $field->save();

            return redirect()->back()->with('success', 'Estado cambiado correctamente.');
        }

    public function unassignUsers(Request $request, $formularioId)
        {
            $formulario = Form::findOrFail($formularioId);
            $userIds = $request->input('user_ids', []);

            // Desasigna los usuarios seleccionados
            $formulario->assignedUsers()->detach($userIds);

            return back()->with('success', 'Usuarios desasignados del formulario correctamente.');
        }

    public function assignUsers(Request $request, $formularioId)
        {
            $formulario = Form::findOrFail($formularioId);
            $userIds = $request->input('user_ids', []); // IDs de usuarios seleccionados

            $formulario->assignedUsers()->attach($userIds);

            return back()->with('success', 'Usuarios asignados al formulario correctamente.');
        }




        

}

