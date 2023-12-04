{{-- form_responses.show.blade.php --}}



<div class="container">
    <h1>Respuestas para: {{ $form->name }}</h1>

    @foreach($form->fields as $field)
        <h3>{{ $field->label }}</h3>
        @php
            $responses = $form->responses->map(function($response) use ($field) {
                return $response->response_data[$field->id] ?? null;
            });
        @endphp
        <ul>
            @foreach($responses as $response)
                <li>{{ $response }}</li> {{-- Muestra cada respuesta --}}
            @endforeach
        </ul>
    @endforeach
</div>

