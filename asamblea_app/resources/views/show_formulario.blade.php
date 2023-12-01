{{-- resources/views/lista_formularios.blade.php --}}



    <h1>Lista de Formularios</h1>
    <div>
        @foreach($formularios as $formulario)
            <div>
                <h2>{{ $formulario->name }}</h2>
                <ul>
                    @foreach($formulario->fields as $field)
                        <li>
                            {{ $field->label }} ({{ $field->type }})
                            @if($field->type === 'multiple' || $field->type === 'yes_no')
                                @if(is_array($field->options))
                                    <ul>
                                        {{-- Ahora se asume que las opciones ya son un array --}}
                                        @foreach($field->options as $option)
                                            <li>{{ $option }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

