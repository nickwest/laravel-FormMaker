@formmaker_component($Field->view_namespace.'::components.field', ['Field' => $Field, 'prev_inline' => $prev_inline])

    @slot('field_markup')
        @formmaker_include($Field->view_namespace.'::pieces.label', ['Field' => $Field])

        @if($view_only)
            <div class="{{ $Field->input_wrapper_class.($view_only ? ' value' : '') }}">
                {!! nl2br($Field->value) !!}
            </div>
        @else
            <textarea {!! $Field->attributes !!}>{!! $Field->value !!}</textarea>
        @endif

        @formmaker_include($Field->view_namespace.'::pieces.example')
        @formmaker_include($Field->view_namespace.'::pieces.error')
        @formmaker_include($Field->view_namespace.'::pieces.note')
    @endslot

@endcomponent
