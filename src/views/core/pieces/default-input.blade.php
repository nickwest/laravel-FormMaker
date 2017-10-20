@component('form-maker::core.components.field', ['Field' => $Field])

    @slot('field_markup')
        @include('form-maker::core.pieces.label', ['Field' => $Field])

        <input {!! $Field->attributes !!} />

        @include('form-maker::core.pieces.example')
        @include('form-maker::core.pieces.error')
        @include('form-maker::core.pieces.note')
    @endslot

@endcomponent
