<div id="field-{{ $Field->name.($Field->multi_key != '' ? '_'.$Field->multi_key : '') }}" class="control">

    @if(is_array($Field->options))
        <fieldset>
            @if($Field->label != '')
                <legend>{!! $Field->label.($Field->label_postfix != '' ? $Field->label_postfix : '').($Field->is_required ? ' <em>*</em>' : '') !!}</legend>
            @endif
            @foreach($Field->options as $key => $option)
                {!! $Field->makeOptionView($key) !!}
            @endforeach
        </fieldset>
    @else
        <label class="radio" for={{ $Field->attributes->id }}>
            <input {!! $Field->attributes !!}>
            {{ $Field->label }}
        </label>
    @endif

    @include('form-maker::pieces.note')
    @include('form-maker::pieces.error')

</div>
