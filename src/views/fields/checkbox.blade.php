@formmaker_component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        <div class="{{ $Field->option_wrapper_class }}">
            <label class="{{ $Field->option_label_class }}" for={{ $Field->attributes->id }}>
                <input {!! $Field->attributes !!}>
                {{ $Field->label }}
            </label>
        </div>

        @formmaker_include($Field->view_namespace.'::pieces.example')
        @formmaker_include($Field->view_namespace.'::pieces.error')
        @formmaker_include($Field->view_namespace.'::pieces.note')
    @endslot

@endcomponent
