@component('form-maker::core.components.field', ['Field' => $Field])

    @slot('field_markup')
        @include('form-maker::core.pieces.label')

        <select {!! $Field->attributes !!}>
            @foreach($Field->options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>

        @include('form-maker::core.pieces.error')
        @include('form-maker::core.pieces.note')
    @endslot

@endcomponent
