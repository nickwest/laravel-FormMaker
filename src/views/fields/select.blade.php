<div id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}" class="field {{ $field->type }}{{ ($field->error_message ? ' error' : '') }} {{ $field->class }}">
    @if($field->label != '')
        {!! Form::rawLabel($field->attributes['id'], $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '')) !!}
    @endif

    {!! Form::select($field->name.($field->multi_key || $field->is_multi ? '['.$field->multi_key.']' : ''), $field->options, $field->value, array('id' => $field->attributes['id'], 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}

    @include('form-maker::pieces.note')
    @include('form-maker::pieces.error')
</div>
