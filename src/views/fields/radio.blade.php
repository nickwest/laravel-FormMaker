@formmaker_component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        <label class="{{ $Field->label_class }}" for={{ $Field->attributes->id }}>
            <input {!! $Field->attributes !!}>
            {{ $Field->label }}
        </label>

        @formmaker_include($Field->view_namespace.'::pieces.example')
        @formmaker_include($Field->view_namespace.'::pieces.error')
        @formmaker_include($Field->view_namespace.'::pieces.note')
    @endslot

@endcomponent
