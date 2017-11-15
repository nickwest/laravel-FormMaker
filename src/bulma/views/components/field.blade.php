@if($Field->is_inline && !$prev_inline)
    @include($Field->view_namespace.'::components.inline-start')
@endif

<div id="field-{{ $Field->attributes->name.($Field->multi_key != '' ? '_'.$Field->multi_key : '') }}" class="field {{ ($Field->attributes->required ? 'required ' : '') }}{{ $Field->container_class }}">
	{!! $field_markup !!}
</div>

@if(!$Field->is_inline && $prev_inline)
    @include($Field->view_namespace.'::components.inline-end')
@endif
