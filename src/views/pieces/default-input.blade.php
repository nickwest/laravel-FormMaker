@component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        @include('form-maker::pieces.label', ['Field' => $Field])

        <input {!! $Field->attributes !!} />

        @include('form-maker::pieces.example')
        @include('form-maker::pieces.error')
        @include('form-maker::pieces.note')
    @endslot

@endcomponent
