@component('form-maker::core.components.field', ['Field' => $Field])

    @slot('field_markup')
        <label class="{{ $Field->label_class }}" for={{ $Field->attributes->id }}>
            <input {!! $Field->attributes !!}>
            {{ $Field->label }}
        </label>

        @include('form-maker::core.pieces.error')
        @include('form-maker::core.pieces.note')
    @endslot

@endcomponent
