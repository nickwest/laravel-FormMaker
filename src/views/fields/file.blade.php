@component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        @include('form-maker::pieces.label', ['Field' => $Field])

        @if($Field->value == '')
            <input {!! $Field->attributes !!} />
        @else
            <div class="file-link">
                {{ $Field->value }}
                <input type="submit" value="remove" name="{{ $Field->attributes->name }}" />
            </div>
        @endif

        @include('form-maker::pieces.error')
        @include('form-maker::pieces.note')
    @endslot

@endcomponent
