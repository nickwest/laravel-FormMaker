@component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        <label class="{{ $Field->label_class }}" for={{ $Field->attributes->id }}>
            <input {!! $Field->attributes !!}>
            {{ $Field->label }}
        </label>

        @include('form-maker::pieces.error')
        @include('form-maker::pieces.note')
    @endslot

@endcomponent
