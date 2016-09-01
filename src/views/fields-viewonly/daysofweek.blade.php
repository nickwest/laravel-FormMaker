<div class="days_of_week">
	<label>{{{ $field->label }}}</label>

	<?php $i = 0; ?>
	@foreach($daysofweek as $key => $day)
		<?php $i++; ?>
		<div class="day"><label for="{{{ $field->id }}}_{{ $i }}">{{ $day }}</label><input type="checkbox" name="{{{ $field->name }}}[]" value="{{ $key }}" id="{{{ $field->id }}}_{{ $i }}" {{ ($field->value[$key] ? 'checked="checked" ' : '' ) }} disabled /></div>
	@endforeach

</div>
