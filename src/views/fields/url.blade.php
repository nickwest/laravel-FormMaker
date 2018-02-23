{{--
    This template can be overriden to be unique,
    by default it uses the default input markup
 --}}

{{--  Custom view only view  --}}
@if($view_only)
    @formmaker_component($Field->view_namespace.'::components.field', ['Field' => $Field, 'prev_inline' => $prev_inline])

    @slot('field_markup')
        @formmaker_include($Field->view_namespace.'::pieces.label', ['Field' => $Field])

        <div class="value">
            <a href="{{ $Field->value }}">{{ $Field->value }}</a>
        </div>

        @formmaker_include($Field->view_namespace.'::pieces.note')
    @endslot

    @endcomponent

    {{--  Use default input template for non-view only --}}
@else
    @include("form-maker::pieces.default-input")
@endif
