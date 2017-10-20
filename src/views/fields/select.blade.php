@formmaker_component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        @include('form-maker::pieces.label')

        <div class="{{ $Field->input_wrapper_class }}">
            <select {!! $Field->attributes !!}>
                @foreach($Field->options as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        @formmaker_include($Field->view_namespace.'::pieces.example')
        @formmaker_include($Field->view_namespace.'::pieces.error')
        @formmaker_include($Field->view_namespace.'::pieces.note')
    @endslot

@endcomponent
