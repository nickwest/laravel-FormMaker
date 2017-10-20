@formmaker_component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        <div class="{{ $Field->options_container_class }}">
            @if($Field->label != '')
                <label class="{{ $Field->label_class }}">{!! $Field->label.($Field->label_postfix != '' ? $Field->label_postfix : '').($Field->is_required ? ' <em>*</em>' : '') !!}</label>
            @endif
            @foreach($Field->options as $key => $option)
                {!! $Field->makeOptionView($key) !!}
            @endforeach
        </div>

        @formmaker_include($Field->view_namespace.'::pieces.example')
        @formmaker_include($Field->view_namespace.'::pieces.error')
        @formmaker_include($Field->view_namespace.'::pieces.note')
    @endslot

@endcomponent
