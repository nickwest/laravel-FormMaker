<fieldset{!! (isset($fieldset_id) ? ' id="'.$fieldset_id.'"' : '') !!}{!! (isset($fieldset_class) ? ' class="'.$fieldset_class.'"' : '') !!}>

@if(isset($legend) && $legend != '')
    <legend>{{ $legend }}</legend>
@endif

@foreach($Form->getDisplayFields() as $field)
    {!! $Field->makeView() !!}
@endforeach

</fieldset>
