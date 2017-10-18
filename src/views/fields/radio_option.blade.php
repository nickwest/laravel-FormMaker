<div class="option">
    {!! Form::radio($field->name.($field->multi_key || $field->is_multi ? '['.$field->multi_key.']' : ''), $key, ($field->value == $key ? true : false), $field->attributes) !!}
    {!! Form::rawLabel($field->attributes['id'].'_'.$key, $field->options[$key]) !!}
</div>
