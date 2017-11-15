@formmaker_component($Field->view_namespace.'::components.field', ['Field' => $Field, 'prev_inline' => $prev_inline])

    @slot('field_markup')
        @include($Field->view_namespace.'::pieces.label', ['Field' => $Field])

        @if($Field->value == '')
            <input {!! $Field->attributes !!} />
        @else
            <div class="file-link">
                {{ $Field->value }}
                <input type="submit" value="{{ $Field->delete_button_value }}" name="{{ $Field->attributes->name }}" />
            </div>
        @endif

        @formmaker_include($Field->view_namespace.'::pieces.example')
        @formmaker_include($Field->view_namespace.'::pieces.error')
        @formmaker_include($Field->view_namespace.'::pieces.note')
    @endslot

@endcomponent
