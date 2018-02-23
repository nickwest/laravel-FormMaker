<div id="field-{{ $Field->attributes->name.($Field->multi_key != '' ? '_'.$Field->multi_key : '') }}" class="type-{{ $Field->attributes->type }} field {{ ($Field->attributes->required ? 'required ' : '') }}{{ $Field->container_class }}">

	{!! $field_markup !!}

</div>
