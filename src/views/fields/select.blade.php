<div class="field">
    @if($Field->label != '')
        <label class="label" for="{{ $Field->attributes->id }}">{{ $Field->label }}</label>
    @endif

    <div class="control">
        <div class="select">
            <select {!! $Field->attributes !!}>
                @foreach($Field->options as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @include('form-maker::pieces.error')
    @include('form-maker::pieces.note')

</div>
