@component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        <div class="{{ $Field->option_wrapper_class }}">
            <label class="{{ $Field->option_label_class }}" for={{ $Field->attributes->id }}>
                <input {!! $Field->attributes !!}>
                {{ $Field->label }}
            </label>
        </div>

        @include('form-maker::pieces.error')
        @include('form-maker::pieces.note')
    @endslot

@endcomponent
