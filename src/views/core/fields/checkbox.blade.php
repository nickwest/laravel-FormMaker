@component('form-maker::core.components.field', ['Field' => $Field])

    @slot('field_markup')
        <div class="{{ $Field->option_wrapper_class }}">
            <label class="{{ $Field->option_label_class }}" for={{ $Field->attributes->id }}>
                <input {!! $Field->attributes !!}>
                {{ $Field->label }}
            </label>
        </div>

        @include('form-maker::core.pieces.error')
        @include('form-maker::core.pieces.note')
    @endslot

@endcomponent
