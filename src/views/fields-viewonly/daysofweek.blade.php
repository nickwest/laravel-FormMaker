<div class="days_of_week">
	<label>{{{ $field->label }}}</label>

	<?php $i = 0; ?>
	@foreach($daysofweek as $key => $day)
		<?php $i++; ?>
			@if($field->value[$key])
				<div class="day">{{ $day }}</div>
			@endif
	@endforeach

</div>
