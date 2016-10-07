<fieldset{!! (isset($fieldset_id) ? ' id="'.$fieldset_id.'"' : '') !!}{!! (isset($fieldset_class) ? ' class="'.$fieldset_class.'"' : '') !!}>

@if(isset($legend) && $legend != '')
	<legend>{{ $legend }}</legend>
@endif

@foreach($Form->getDisplayFields() as $field)
	@if($field->type != 'custom')
		@include('form-maker::fields.'.$field->type, array('field' => $field))
	@else
		@include($field->view, array('field', $field))
	@endif
@endforeach

</fieldset>
