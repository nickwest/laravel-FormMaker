@if($Field->is_inline)
<div class="inline_fields">
@endif
<div id="field-{{ $Field->attributes->name.($Field->multi_key != '' ? '_'.$Field->multi_key : '') }}" class="field {{ ($Field->attributes->required ? 'required ' : '') }}{{ $Field->container_class }}">

	{!! $field_markup !!}

</div>
