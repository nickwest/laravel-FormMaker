<div id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}" class="field {{ $field->type }}{{ ($field->error_message ? ' error' : '') }}  {{ $field->class }}">

    @if(is_array($field->options))
        @if($field->label != '')
            <div class="field_label"><strong>{!! $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '') !!}</strong></div>
        @endif

        @foreach($field->options as $value => $label)
            <label class="checkbox" for="{{ $field->attributes['id'] }}_{{ $loop->index }}">
                <input
                    type="checkbox"
                    value="{{ $value }}"
                    {{ isset($field->value[$field->options]) ? 'checked="checked"' : '' }}
                    name="{{ $field->name.($field->multi_key || $field->is_multi ? '['.$field->multi_key.']' : '') }}"
                    id="{{ $field->attributes['id'] }}_{{ $loop->index }}"
                    class="{{ (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ) }}"
                />
                {{ $label }}
            </label>
        @endforeach

    @else
        <label class="checkbox" for="{{ $field->attributes['id'] }}">
            <input
                type="checkbox"
                value="{{ $field->options }}"
                {{ $field->is_disabled ? 'disabled="disabled"' : '' }}
                {{ isset($field->value[$field->options]) ? 'checked="checked" ' : '' }}
                name="{{ $field->name.($field->multi_key || $field->is_multi ? '['.$field->multi_key.']' : '') }}"
                id="{{ $field->attributes['id'] }}"
                class="{{ (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ) }}"
            />
            {{ $field->label }}
        </label>
    @endif

    @include('form-maker::pieces.note')
    @include('form-maker::pieces.error')
</div>
