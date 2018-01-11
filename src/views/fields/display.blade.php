{{--  This is a really raw general purpose view that will show raw values as plain text  --}}
@formmaker_component($Field->view_namespace.'::components.field', ['Field' => $Field, 'prev_inline' => $prev_inline])

    @slot('field_markup')
        @formmaker_include($Field->view_namespace.'::pieces.label', ['Field' => $Field])

            @if(is_array($Field->value))
                @foreach($Field->value as $value)
                    @if(count($Field->options) > 0 && isset($Field->options[$value]))
                        {{ $Field->options[$value] . ($loop->last ? '' : ', ') }}
                    @else
                        {{ $value . ($loop->last ? '' : ', ') }}
                    @endif
                @endforeach
            @else
                <div class="value">
                    @if(count($Field->options) > 0 && isset($Field->options[$Field->value]))
                        {{ $Field->options[$Field->value] }}
                    @else
                        @if($Field->attributes->type == 'textarea')
                            {!! nl2br(htmlspecialchars($Field->value)) !!}
                        @else
                            {{ $Field->value }}
                        @endif
                    @endif
                </div>
            @endif

    @endslot
@endcomponent
