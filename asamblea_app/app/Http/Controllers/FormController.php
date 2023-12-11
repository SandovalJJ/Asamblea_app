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
            $response_data[$field->label] = $request->input($field_name);
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
        $usersNotVotedByQuestion = [];
        $allUsers = User::pluck('name', 'id');
        $votesCountByQuestion = [];

        foreach ($form->fields as $field) {
            // Contar cuántos usuarios han votado por esta pregunta específica
            $votesCountByQuestion[$field->label] = $responses->filter(function ($response) use ($field) {
                return isset($response->response_data[$field->label]) && !is_null($response->response_data[$field->label]);
            })->count();
        }

        foreach ($form->fields as $field) {
            // IDs de usuarios que han respondido a esta pregunta
            $respondedUserIds = $responses->filter(function ($response) use ($field) {
                return isset($response->response_data[$field->label]);
            })->pluck('user_id')->unique();
    
            // Usuarios que no han respondido a esta pregunta
            $usersNotVotedByQuestion[$field->label] = $allUsers->except($respondedUserIds)->values();
        }

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
        

        return view('respuestas', compact('responsePercentages', 'responseCounts', 'formFields', 'usersNotVotedByQuestion', 'votesCountByQuestion', 'form'));
    }


    // prueba de generacion de graficos
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
    $form = Form::with('fields')->findOrFail($formId);
    $responses = FormResponse::where('form_id', $formId)->get();
    $responseCounts = [];
    $responsePercentages = [];
    $totalUsers = User::count();
    $formFields = $form->fields;
    $usersNotVotedByQuestion = [];
    $allUsers = User::pluck('name', 'id');
    $votesCountByQuestion = [];

    foreach ($form->fields as $field) {
        // Contar cuántos usuarios han votado por esta pregunta específica
        $votesCountByQuestion[$field->label] = $responses->filter(function ($response) use ($field) {
            return isset($response->response_data[$field->label]) && !is_null($response->response_data[$field->label]);
        })->count();
    }

    foreach ($form->fields as $field) {
        // IDs de usuarios que han respondido a esta pregunta
        $respondedUserIds = $responses->filter(function ($response) use ($field) {
            return isset($response->response_data[$field->label]);
        })->pluck('user_id')->unique();

        // Usuarios que no han respondido a esta pregunta
        $usersNotVotedByQuestion[$field->label] = $allUsers->except($respondedUserIds)->values();
    }

    // Resto de tu código para calcular las respuestas y los votos...

    // Crear una nueva instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Configura el formato y estilo del PDF
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->SetAutoPageBreak(true, 10);

    // Agrega contenido al PDF basado en los datos obtenidos
    foreach ($formFields as $field) {
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, utf8_decode($field->label));
        $pdf->Ln(10);

        // Agrega los porcentajes de respuestas
        if (isset($responsePercentages[$field->label])) {
            foreach ($responsePercentages[$field->label] as $answer => $percentage) {
                $pdf->MultiCell(0, 10, utf8_decode($answer . ': ' . $percentage . '%'));
                $pdf->Ln(10);
            }
        }

        // Agrega el número de votos
        $pdf->MultiCell(0, 10, utf8_decode('Número de votos: ' . ($votesCountByQuestion[$field->label] ?? 0)));
        $pdf->Ln(10);

        // Agrega los usuarios que faltan por votar
        $pdf->MultiCell(0, 10, utf8_decode('Personas que faltan por votar en esta pregunta:'));
        $pdf->Ln(10);
        foreach ($usersNotVotedByQuestion[$field->label] ?? [] as $userName) {
            $pdf->MultiCell(0, 10, utf8_decode($userName));
            $pdf->Ln(10);
        }
    }

    // Descarga el PDF
    $pdf->Output('resultado_formulario.pdf', 'D');
}
}