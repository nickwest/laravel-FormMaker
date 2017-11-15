@formmaker_component($Field->view_namespace.'::components.field', ['Field' => $Field, 'prev_inline' => $prev_inline])

    @slot('field_markup')
        @formmaker_include($Field->view_namespace.'::pieces.label')

        <div class="{{ $Field->input_wrapper_class }}{{ $Field->multiple ? ' is-multiple' : '' }}">
            <select {!! $Field->attributes !!}>
                @foreach($Field->options as $key => $value)
                    @formmaker_include($Field->view_namespace.'::fields.select_option')
                @endforeach
            </select>
        </div>
        @formmaker_include($Field->view_namespace.'::pieces.example')
        @formmaker_include($Field->view_namespace.'::pieces.error')
        @formmaker_include($Field->view_namespace.'::pieces.note')
    @endslot

@endcomponent
