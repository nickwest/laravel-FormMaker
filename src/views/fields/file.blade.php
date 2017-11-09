@formmaker_component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        @include('form-maker::pieces.label', ['Field' => $Field])

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
