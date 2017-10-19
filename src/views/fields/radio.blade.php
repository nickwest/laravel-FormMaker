<div id="field-{{ $Field->name.($Field->multi_key != '' ? '_'.$Field->multi_key : '') }}" class="field">
    <div class="control">
        @if(is_array($Field->options))
            <div class="options">
                @if($Field->label != '')
                    <label class="label">{!! $Field->label.($Field->label_postfix != '' ? $Field->label_postfix : '').($Field->is_required ? ' <em>*</em>' : '') !!}</label>
                @endif
                @foreach($Field->options as $key => $option)
                    {!! $Field->makeOptionView($key) !!}
                @endforeach
            </div>
        @else
            <label class="radio" for={{ $Field->attributes->id }}>
                <input {!! $Field->attributes !!}>
                {{ $Field->label }}
            </label>
        @endif
    </div>

    @include('form-maker::pieces.note')
    @include('form-maker::pieces.error')

</div>
